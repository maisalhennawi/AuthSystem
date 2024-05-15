<?php

namespace App\Listeners;

use App\Events\EmailVerificationEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class EmailVerificationListener
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
     * @param  \App\Events\EmailVerificationEvent  $event
     * @return void
     */
    public function handle(EmailVerificationEvent $event)
    {
        $verificationCode = Str::random(6);
        $verificationCodeExpiresAt = now()->addMinutes(3);
    }
}
