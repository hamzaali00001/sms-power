<?php

namespace App\Console\Commands;

use AfricasTalking\SDK\AfricasTalking;
use App\Models\Contact;
use App\Models\ScheduledMessage;
use App\Models\SentMessage;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SendScheduledSMS extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:scheduledsms';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Scheduled SMS Messages';

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
        $sm = ScheduledMessage::whereBetween('send_time', [Carbon::now()->subMinutes(5), Carbon::now()])->get();

        foreach ($sm as $msg) {
            // Send single scheduled message
            if (is_null($msg->group_id)) {
                $to = $msg->to;
                SentMessage::create([
                    'user_id' => $msg->user_id,
                    'from' => $msg->from,
                    'to' => $msg->to,
                    'message' => $msg->message,
                    'msg_count' => $msg->msg_count,
                    'characters' => $msg->characters,
                    'cost' => $msg->cost
                ]);
            }

            // Send bulk scheduled messages
            if (!is_null($msg->group_id)) {
                $to = Contact::active()->where('group_id', $msg->group_id)->pluck('mobile')->toArray();
                foreach ($to->chunk(100) as $key => $value) {
                    SentMessage::create([
                        'user_id' => $msg->user_id,
                        'from' => $msg->from,
                        'to' => $value,
                        'message' => $msg->message,
                        'msg_count' => $msg->msg_count,
                        'characters' => $msg->characters,
                        'cost' => $msg->cost
                    ]);
                }
            }

            // Send the message
            $sms = $this->africastalking()->sms();

            $sms->send([
                'message' => $msg->message,
                'to' => $to,
                'enqueue' => 'true'
            ]);

            // Delete the message from scheduled messages
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
