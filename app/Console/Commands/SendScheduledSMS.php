<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\SentMessage;
use App\Models\ScheduledMessage;
use Illuminate\Console\Command;
use AfricasTalking\SDK\AfricasTalking;

class SendScheduledSMS extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:scheduled:sms';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Scheduled Messages';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        /*
        ScheduledMessage::chunk(100, function ($messages) {
            foreach ($messages as $msg) {
                if (Carbon::create(strtotime($msg->send_time))->between(Carbon::now()->subMinutes(5), Carbon::now())) {
                    dispatch(new \App\Jobs\SendScheduledSMS())->onQueue('send-scheduled-sms');
                }
            }
        });
        */

        foreach (ScheduledMessage::all() as $msg) {
            if ($msg->send_time && $msg->send_time->between(Carbon::now()->subMinutes(5),Carbon::now())) {
                //dispatch(new \App\Jobs\SendScheduledSms($msg))->onQueue('send-scheduled-sms');
                SentMessage::create([
                    'user_id' => $msg->user_id,
                    'from' => $msg->from,
                    'to' => $msg->to,
                    'message' => $msg->message,
                    'msg_count' => $msg->msg_count,
                    'characters' => $msg->msg_characters,
                    'cost' => $msg->cost
                ]);
            }

            // Send the message
            $sms = $this->africastalking()->sms();

            $sms->send([
                'message' => $msg->message,
                'to' => $msg->to,
                'enqueue' => 'true'
            ]);

            // Delete the scheduled message;
            $msg->delete();
        }
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
