<?php

namespace App\Mail;

use App\Contracts\CustomMailable;
use Illuminate\View\View;

class TestMail implements CustomMailable
{

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return view('mails.test');
    }
}
