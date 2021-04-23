<?php

namespace App\Listeners;

use App\Events\shouldSendEmail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Mail;

class SendEmailNotification
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
     * @param  shouldSendEmail  $event
     * @return void
     */
    public function handle(shouldSendEmail $event)
    {
        return Mail::send($event->mail);


    }
}