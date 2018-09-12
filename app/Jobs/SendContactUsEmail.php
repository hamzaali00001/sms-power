<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;
use App\Mail\Backend\ContactUs;

class SendContactUsEmail implements ShouldQueue
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
        $data = [
            'email'=>auth()->user()->email,
            'name' => auth()->user()->name,
            'phone' => auth()->user()->mobile,
            'subject' => request('subject'),
            'message' => request('message')
        ];

        $email = new ContactUs($data);

        Mail::to(env('ADMIN_EMAIL'))->send($email);
    }
}
