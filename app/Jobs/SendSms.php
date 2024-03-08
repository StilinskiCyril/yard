<?php

namespace App\Jobs;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendSms implements ShouldQueue
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
    public function handle(): void
    {
        try {
            $client = new Client();
            $response = $client->request('POST', 'https://api.mobilesasa.com/v1/send/message', [
                'form_params' => [
                    'senderID' => $this->data['senderId'],
                    'message' => $this->data['message'],
                    'phone' => $this->data['msisdn']
                ],
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/x-www-form-urlencoded',
                    'Authorization' => 'Bearer '.config('mobilesasa.bearer_token')
                ],
            ]);

            $mobilesasaApiResponse = json_decode($response->getBody()->getContents());
            if ($mobilesasaApiResponse->responseCode == '0200'){
                Log::alert("Message sent successfully");
            } else {
                Log::alert("Message not sent");
            }
        } catch (GuzzleException $e){
            Log::info("Send SMS guzzle exception:\n {$e->getMessage()}");
        } catch (\Exception $exception){
            Log::info("Send SMS exception:\n {$exception->getMessage()}");
        }
    }
}
