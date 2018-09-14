<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\DB;
use App\Models\SentMessage;
use App\Models\Contact;
use AfricasTalking\SDK\AfricasTalking;

class SendScheduledSMS implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        DB::transaction(function () {
            if ($this->message->recipient) {
                $to = $this->recipient;
                SentMessage::create([
                    'user_id' => $msg->user_id,
                    'from' => $msg->from,
                    'to' => $msg->to,
                    'message' => $msg->message,
                    'msg_count' => $msg->msg_count,
                    'characters' => $msg->msg_characters,
                    'cost' => $msg->cost
                ]);
            } elseif (!is_null($this->message->group_id)) {
                $to = Contact::where('group_id', request('to'))->pluck('mobile')->toArray();
                foreach ($to as $key => $value) {
                    SentMessage::create([
                        'user_id' => $this->user_id,
                        'from' => $this->from,
                        'to' => $value,
                        'message' => $this->message,
                        'msg_count' => $this->msg_count,
                        'characters' => $this->characters,
                        'cost' => $this->cost
                    ]);
                }
            }

            // Send the message
            $sms = $this->africastalking()->sms();

            $sms->send([
                'message' => $this->message,
                'to' => $to,
                'enqueue' => 'true'
            ]);

            flash()->success('Your scheduled messages have been sent out.');
        });
    }

    /**
     * Instantiate a new AfricasTalking instance.
     *
     * @return AfricasTalking\SDK\AfricasTalking;
     */
    protected function africastalking()
    {
        $username = env('AFRICASTALKING_USERNAME');
        $apiKey = env('AFRICASTALKING_API_KEY');

        return new AfricasTalking($username, $apiKey);
    }
}
