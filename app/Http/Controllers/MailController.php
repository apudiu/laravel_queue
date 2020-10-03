<?php

namespace App\Http\Controllers;

use App\Jobs\SendTestMail;

class MailController extends Controller
{

    public function sendMail() :void
    {

        // 1. Send directly

        // $status = $customMailer
        //     ->to('chockhong1@gmail.com', 'ApuApuApu')
        //     ->subject('Test mail from new implementation v2.m2gv.1')
        //     ->mailer(new TestMail())
        //     ->send()
        // ;

        // Debug information is available
        // dd($status, $customMailer->getUsedProvider(), $customMailer->getDebugLog());

        // 2. Send using mailable
        // new TestMail();

        // 3. Send using queue, This can be integrated into mailable/concrete too,
        // but we didn't did that here

        SendTestMail::dispatch();

    }

}
