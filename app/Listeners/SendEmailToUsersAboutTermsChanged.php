<?php

namespace App\Listeners;

use App\Events\TermsChanged;
use App\Jobs\SendTermsChangedEmail;
use App\Mail\TermsChangedEmail;

class SendEmailToUsersAboutTermsChanged
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
     * @param  TermsChanged  $event
     * @return void
     */
    public function handle(TermsChanged $event)
    {
        dispatch(new SendTermsChangedEmail());
    }
}
