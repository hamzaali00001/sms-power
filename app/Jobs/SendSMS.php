<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\DB;
use App\Models\Group;
use App\Models\Contact;
use App\Models\SentMessage;
use AfricasTalking\SDK\AfricasTalking;
use Carbon\Carbon;

class SendSMS implements ShouldQueue
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
        // Check user credit balance
        $this->checkBalance();

        DB::transaction(function () {
            if (request('type') === 'single') {
                $to = request('to');
                SentMessage::create([
                    'user_id' => auth()->user()->id,
                    'from' => request('from'),
                    'to' => request('to'),
                    'message' => request('message') .' '. env('OPT_OUT'),
                    'msg_count' => $this->msgCount(),
                    'characters' => utf8_encode(strlen(request('message'))),
                    'cost' => $this->smsCost()
                ]);
            } 

            if (request('type') === 'bulk') {
                $to = Contact::where('group_id', request('to'))->pluck('mobile')->toArray();
                foreach ($to as $key => $value) {
                    SentMessage::create([
                        'user_id' => auth()->user()->id,
                        'from' => request('from'),
                        'to' => $value,
                        'message' => request('message') .' '. env('OPT_OUT'),
                        'msg_count' => $this->msgCount(),
                        'characters' => utf8_encode(strlen(request('message'))),
                        'cost' => $this->smsCost()
                    ]);
                }
            }

            // Send the message
            $sms = $this->africastalking()->sms();

            $sms->send([
                'message' => request('message') .' '. env('OPT_OUT'),
                'to' => $to,
                'enqueue' => 'true'
            ]);

            // Update user's credit;
            $this->updateBalance();

            flash()->success('Your message has been sent out.');
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

    /**
     * Check the user's balance.
     */
    private function checkBalance()
    {
        if (auth()->user()->creditBalance() < $this->totalCost()) {
            
            flash()->error('Your message cannot be sent due to insufficient credit balance.');

            return redirect()->back();
        }
    }

    /**
     * Update the user's balance.
     */
    private function updateBalance()
    {
        if (!auth()->user()->hasRole('admin')) {
            auth()->user()->update([
               'credit' => auth()->user()->credit - $this->totalCost()
            ]);
        }
    }

    /**
     * Calculate the total message parts.
     */
    private function msgCount()
    {
        $msg = request('message');
        $final_msg = $msg . ' ' . env('OPT_OUT');

        if (strlen($final_msg) <= 160) {
            return $msg_count = 1;
        } else {
            return $msg_count = ceil(utf8_encode(strlen($final_msg))/env('SMS_LENGTH'));
        }
    }

    /**
     * Get the cost of sending a single message.
     */
    private function smsCost()
    {
        if (empty(auth()->user()->sms_cost)) {
            return $sms_cost = env('SMS_COST');
        } else {
            return $sms_cost = auth()->user()->sms_cost;
        }
    }

    /**
     * Calculate the total cost of sending the message(s).
     */
    private function totalCost()
    {
        return number_format($this->msgCount() * $this->recipients() *  $this->smsCost(), 2);
    }

    /**
     * Get the total recipients.
     */
    private function recipients()
    {
        if (request('type') === 'bulk') {
            return $recipients = count(Contact::where('group_id', request('recipients'))->pluck('mobile'));
        } elseif (request('type') === 'single') {
            return $recipients = 1;
        }
    }
}
