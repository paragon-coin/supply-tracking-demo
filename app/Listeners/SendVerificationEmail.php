<?php

namespace App\Listeners;

use App\Events\UserRequestedVerificationEmail;
use App\Mail\SendVerificationToken;
use Mail;

class SendVerificationEmail
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  $event
     * @return void
     */
    public function handle($event)
    {
        $event->user->notify(new \App\Notifications\SendVerificationToken($event->user->verificationToken));
    }
}
