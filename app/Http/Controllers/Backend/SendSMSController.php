<?php

namespace App\Http\Controllers\Backend;

use AfricasTalking\SDK\AfricasTalking;
use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\Group;
use App\Models\ScheduledMessage;
use App\Models\SentMessage;
use Carbon\Carbon;

class SendSMSController extends Controller
{
    /**
     * Show the form for sending messages.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $groups = auth()->user()->groups()->withCount([
            'contacts' => function($query) {
                $query->active();
            }
        ])
        ->has('contacts', '>', 0)
        ->orderBy('name')
        ->get();

        $senderids = auth()->user()->senderids()->active()->orderBy('name')->get();
        $templates = auth()->user()->templates()->orderBy('name')->get();

        return view('backend.send-sms.create', compact(['groups', 'senderids', 'templates']));
    }

    /**
     * Send Single SMS.
     *
     * @return \Illuminate\Http\Response
     */
    public function singleSMS()
    {
        // Check user credit balance and if not sufficient Redirect
        if (!is_null($shouldRedirect = $this->checkBalance())) {
            return $shouldRedirect;
        }

        if (request('schedule') === 'No') {
            SentMessage::create([
                'user_id' => auth()->user()->id,
                'from' => request('from'),
                'to' => request('to'),
                'message' => request('message') .' '. env('OPT_OUT'),
                'msg_count' => $this->msgCount(),
                'characters' => utf8_encode(strlen(request('message'))),
                'cost' => $this->smsCost()
            ]);

            // Send the message
            $sms = $this->africastalking()->sms();

            $sms->send([
                'message' => request('message') .' '. env('OPT_OUT'),
                'from' => request('from') ?? env('SENDER_ID'),
                'to' => request('to'),
                'enqueue' => 'true'
            ]);

            flash()->success('Your message has been sent out.');
        } 

        if (request('schedule') === 'Yes') {
            ScheduledMessage::create([
                'user_id' => auth()->user()->id,
                'from' => request('from'),
                'to' => request('to'),
                'recipients' => 1,
                'message' => request('message') .' '. env('OPT_OUT'),
                'msg_count' => $this->msgCount(),
                'characters' => utf8_encode(strlen(request('message'))),
                'cost' => $this->totalCost(),
                'send_time' => Carbon::createFromTimeString(request('send_time'))
            ]);

            flash()->info('Your message has been scheduled out.');
        }

        // Update the user's credit;
        $this->updateBalance();

        if (request('schedule') === 'No') {
            return redirect()->route('sent-sms.index');
        } else {
            return redirect()->route('scheduled-sms.index');
        }
    }

    /**
     * Send Bulk SMS.
     *
     * @return \Illuminate\Http\Response
     */
    public function bulkSMS()
    {
        // Check user credit balance and if not sufficient Redirect
        if (!is_null($shouldRedirect = $this->checkBalance())) {
            return $shouldRedirect;
        }

        if (request('schedule') === 'No') {
            $recipients = Contact::active()->where('group_id', request('to'))->pluck('mobile')->toArray();
            foreach ($recipients->chunk(100) as $key => $value) {
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

            // Send the message
            $sms = $this->africastalking()->sms();

            $sms->send([
                'message' => request('message') .' '. env('OPT_OUT'),
                'from' => request('from') ?? env('SENDER_ID'),
                'to' => $recipients,
                'enqueue' => 'true'
            ]);

            flash()->success('Your message has been sent out.');
        } 

        if (request('schedule') === 'Yes') {
            $recipients = Contact::active()->where('group_id', request('to'))->pluck('mobile')->toArray();
            ScheduledMessage::create([
                'user_id' => auth()->user()->id,
                'from' => request('from'),
                'recipients' => count($recipients),
                'message' => request('message') .' '. env('OPT_OUT'),
                'msg_count' => $this->msgCount(),
                'characters' => utf8_encode(strlen(request('message'))),
                'cost' => $this->totalCost(),
                'send_time' => Carbon::createFromTimeString(request('send_time'))
            ]);

            flash()->info('Your message has been scheduled out.');
        }

        // Update the user's credit;
        $this->updateBalance();

        if (request('schedule') === 'No') {
            return redirect()->route('sent-sms.index');
        } else {
            return redirect()->route('scheduled-sms.index');
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
            return $recipients = count(Contact::active()->where('group_id', request('to'))->pluck('mobile'));
        } elseif (request('type') === 'single') {
            return $recipients = 1;
        }
    }
}
