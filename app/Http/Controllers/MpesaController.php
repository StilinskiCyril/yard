<?php

namespace App\Http\Controllers;

use App\Enums\PaymentChannel;
use App\Jobs\ProcessPayments;
use App\Models\Company;
use App\Models\CvTemplate;
use App\Models\MpesaAccessToken;
use App\Models\Order;
use App\Models\Package;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class MpesaController extends Controller
{
    // Confirmation url
    public function confirmationUrl(Request $request)
    {
        // IP whitelisting (verify that the request if from mpesa APIs)
//        if (!in_array($request->ip(), explode(',', env('MPESA_WHITELIST_IPS')))){
//            return response()->json([
//                "status" => false,
//                "message" => "UNKNOWN_PAYMENT_REQUEST. UNAUTHORIZED IP"
//            ], 403);
//        }

        $content = $request->all();

        if (env('LOG_MPESA_CALLBACK')) {
            Log::info(json_encode($content));
        }

        ProcessPayments::dispatch([
            'channel' => PaymentChannel::Mpesa,
            'content' => $content,
            'ip' => $request->ip()
        ])->onQueue('process-mpesa-payment');

        return response()->json([
            "ResultCode" => 0,
            "ResultDesc" => "Success"
        ]);
    }

    // Validation url
    public function validationUrl(Request $request)
    {
        /**
         * Consider the following error codes
         *
         * C2B00011 = Invalid MSISDN
         * C2B00012 = Invalid Account Number
         * C2B00013 = Invalid Amount
         * C2B00014 = Invalid KYC Details
         * C2B00015 = Invalid Shortcode
         * C2B00016 = Other Error
         */

        // Is payment for company?
        $company = Company::where('account_no', $request->BillRefNumber)->first();
        if ($company){
            return response()->json([
                "ResultCode" => "0",
                "ResultDesc" => "Accepted",
                "ThirdPartyTransID" => $company->account_no
            ]);
        } else {
            // Is payment for an order
            $order = Order::where('account_no', $request->BillRefNumber)->first();
            if ($order){
                return response()->json([
                    "ResultCode" => "0",
                    "ResultDesc" => "Accepted",
                    "ThirdPartyTransID" => $order->account_no
                ]);
            } else {
                // No match for BillRefNumber (REJECT THE PAYMENT)
                return response()->json([
                    "ResultCode" => "C2B00012", // Invalid account number
                    "ResultDesc" => "Rejected"
                ]);
            }
        }
    }

    // Lipa na m-pesa password
    public function lipaNaMpesaPassword()
    {
        $lipaTime = Carbon::rawParse('now')->format('YmdHms');
        $passkey = config('mpesa.c2b_passkey');
        $BusinessShortCode = config('mpesa.business_shortcode');
        $timestamp = $lipaTime;
        return base64_encode($BusinessShortCode.$passkey.$timestamp);
    }

    // Initiate stk push
    public function stKPush(Request $request)
    {
        $data = MpesaAccessToken::where('type', 'c2b')->first();
        $url = 'https://api.safaricom.co.ke/mpesa/stkpush/v1/processrequest';
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json','Authorization:Bearer '.$data->token));
        $curl_post_data = [
            'BusinessShortCode' => config('mpesa.business_shortcode'),
            'Password' => $this->lipaNaMpesaPassword(),
            'Timestamp' => Carbon::rawParse('now')->format('YmdHms'),
            'TransactionType' => 'CustomerPayBillOnline',
            'Amount' => $request->amount,
            'PartyA' => $request->msisdn,
            'PartyB' => config('mpesa.business_shortcode'),
            'PhoneNumber' =>  $request->msisdn,
            'CallBackURL' => url('api/v1/c2b/stk/transaction/callback'),
            'AccountReference' => $request->account_no,
            'TransactionDesc' => env('APP_NAME')." Payment"
        ];
        $data_string = json_encode($curl_post_data);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
        return curl_exec($curl);
    }

    // Stk push callback url
    public function stkCallback(Request $request)
    {
        $content = $request->all();
        Log::channel('stklog')->info($content['Body']);
    }

    // Register C2B validation & confirmation urls
    public function registerValidationAndConfirmationUrls()
    {
        $url = 'https://api.safaricom.co.ke/mpesa/c2b/v2/registerurl';
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json','Authorization: Bearer '.$this->generateC2bAccessToken()));

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode(array(
            'ShortCode' => config('mpesa.business_shortcode'),
            'ResponseType' => 'Cancelled', // cancel the transaction automatically if validation fails or confirmation url is unreachable
            'ConfirmationURL' => "https://app.kazisolutions.co.ke/api/v1/c2b/779c-5ff2-a5dd-2450/confirmation",
            'ValidationURL' => "https://app.kazisolutions.co.ke/api/v1/c2b/265c-2edf-a5dc-2440/validation"
        )));
        $curl_response = curl_exec($curl);
        echo $curl_response;
    }

    // Generate c2b access token
    public function generateC2bAccessToken()
    {
        $consumer_key = config('mpesa.c2b_consumer_key');
        $consumer_secret = config('mpesa.c2b_consumer_secret');
        $credentials = base64_encode($consumer_key.":".$consumer_secret);
        $url = "https://api.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials";
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array("Authorization: Basic ".$credentials));
        curl_setopt($curl, CURLOPT_HEADER,false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $curl_response = curl_exec($curl);
        $access_token = json_decode($curl_response);
        return $access_token->access_token;
    }
}
