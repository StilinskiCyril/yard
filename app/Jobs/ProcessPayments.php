<?php

namespace App\Jobs;

use App\Enums\PaymentChannel;
use App\Models\Company;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Transaction;
use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Exception;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;

class ProcessPayments implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $data;

    /**
     * Create a new job instance.
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        $channel = $this->data['channel'];
        if ($channel == PaymentChannel::Mpesa){
            $ip = $this->data['ip'];
            $transId = $this->data['content']['TransID'];
            $transTime = Carbon::parse($this->data['content']['TransTime'])->toDateTimeString();
            $amount = $this->data['content']['TransAmount'];
            $oldMsisdn = $this->data['content']['MSISDN'];

            $phoneUtil = PhoneNumberUtil::getInstance();
            $kenyaNumberProto = $phoneUtil->parse($oldMsisdn, "KE");
            $isValid = $phoneUtil->isValidNumber($kenyaNumberProto);
            if ($isValid) {
                $newMsisdn = $phoneUtil->format($kenyaNumberProto, PhoneNumberFormat::E164);
                $msisdn = substr($newMsisdn, 1);
            } else {
                $msisdn = $oldMsisdn;
            }

            $name = $this->data['content']['FirstName'];
            $accountNo = $this->data['content']['BillRefNumber'];
            $businessShortCode = $this->data['content']['BusinessShortCode'];

            /**
             * Process the payment
             */

            // Create the payment
            $payment = Payment::create([
                'channel' => PaymentChannel::Mpesa,
                'amount' => $amount,
                'transaction_id' => $transId,
                'date' => $transTime,
                'notes' => "$name | $msisdn | $accountNo",
                'ip' => $ip
            ]);

            // Is payment for an order?
            $order = Order::where('account_no', $accountNo)->first();
            if ($order){
                // Validate amount
                if ($amount == $order->amount){
                    $payment->transactions()->create([
                        'channel' => PaymentChannel::Mpesa,
                        'amount' => $amount,
                        'type' => 'credit',
                        'model' => 'Order',
                        'model_id' => $order->id,
                        'notes' => "$name | $msisdn | $accountNo",
                        'desc' => 'Order payment from m-pesa'
                    ]);

                    $order->update([
                        'payment_status' => true,
                        'paid_on' => $transTime
                    ]);

                    // TODO Notify user of successful payment
                } elseif($amount < $order->amount) {
                    $payment->transactions()->create([
                        'channel' => PaymentChannel::Mpesa,
                        'amount' => $amount,
                        'type' => 'credit',
                        'model' => 'Order',
                        'model_id' => $order->id,
                        'notes' => "$name | $msisdn | $accountNo",
                        'desc' => 'Order payment | Insufficient amount'
                    ]);

                    // TODO: Notify user of insufficient amount
                } else {
                    $payment->transactions()->create([
                        'channel' => PaymentChannel::Mpesa,
                        'amount' => $amount,
                        'type' => 'credit',
                        'model' => 'Order',
                        'model_id' => $order->id,
                        'notes' => "$name | $msisdn | $accountNo",
                        'desc' => 'Order payment | Excess'
                    ]);
                }
            } else {
                // Is payment for company wallet
                $company = Company::where('account_no', $accountNo)->first();
                if ($company){
                    $payment->transactions()->create([
                        'channel' => PaymentChannel::Mpesa,
                        'amount' => $amount,
                        'type' => 'credit',
                        'model' => 'Company',
                        'model_id' => $company->id,
                        'notes' => "$name | $msisdn | $accountNo",
                        'desc' => 'Company wallet topup from m-pesa'
                    ]);

                    $payment->transactions()->create([
                        'channel' => PaymentChannel::Mpesa,
                        'amount' => -$amount,
                        'type' => 'debit',
                        'model' => 'Company',
                        'model_id' => $company->id,
                        'notes' => "$name | $msisdn | $accountNo",
                        'desc' => 'Company wallet topup from m-pesa | Funds moved company wallet'
                    ]);

                    // Credit company wallet
                    $company->walletTransactions()->create([
                        'amount' => $amount,
                        'type' => 'credit',
                        'notes' => 'Company wallet topup from mpesa'
                    ]);

                    $company->update([
                        'wallet_balance' => $company->wallet_balance + $amount
                    ]);
                } else {
                    // Save payment as GHOST (unknown)
                    $payment->transactions()->create([
                        'channel' => PaymentChannel::Mpesa,
                        'amount' => $amount,
                        'type' => 'credit',
                        'notes' => "$name | $msisdn | $accountNo",
                        'desc' => 'Unknown payment',
                        'status' => false
                    ]);
                }
            }

        } elseif ($channel == PaymentChannel::Card) {
            // process card payment
        } elseif ($channel == PaymentChannel::Paypal) {
            // process paypal payments
        }
        //TODO etc etc add more payment options
    }
}
