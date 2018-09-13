<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\ScheduledMessage;
use Illuminate\Console\Command;

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
        ScheduledMessage::chunk(100, function ($messages) {
            foreach ($messages as $msg) {
                if ($msg->send_time->between(Carbon::now()->subMinutes(5), Carbon::now())) {
                    dispatch(new \App\Jobs\SendScheduledSMS())->onQueue('send-scheduled-sms');
                }
            }
        });

        /*
        foreach (ScheduledMessage::all() as $msg) {
            if ($msg->send_time->between(Carbon::now()->subMinutes(5), Carbon::now())) {
                dispatch(new \App\Jobs\SendScheduledSMS($message))->onQueue('send-scheduled-sms');
            }
        }
        */
    }
}
