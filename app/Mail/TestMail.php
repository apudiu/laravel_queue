<?php

namespace App\Mail;

use App\Contracts\CustomMailable;
use App\Contracts\CustomMailer;
use Illuminate\View\View;

class TestMail implements CustomMailable
{

    /**
     * Create a new message instance.
     *
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function __construct()
    {
        // Resolving mailer from service container
        $this->build(app()->make(CustomMailer::class));
    }

    /**
     * Build the message.
     *
     * @param CustomMailer $customMailer
     * @return bool
     */
    public function build(CustomMailer $customMailer) :bool
    {
        // Sending ...
        // Sending status is available
        $status = $customMailer
            ->to('chockhong1@gmail.com', 'ApuApuApu')
            ->subject('Test mail from new implementation v2.m2gv.1')
            ->mailer($this->mailView())
            ->send()
        ;

        var_dump("Mail sent using provider: {$customMailer->getUsedProvider()['host']}");

        return $status;
    }

    /**
     * @return View
     */
    public function mailView() :View
    {
        return view('mails.test');
    }
}
