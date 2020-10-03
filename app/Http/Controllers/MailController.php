<?php

namespace App\Http\Controllers;

use App\Contracts\CustomMailer;
use App\Mail\TestMail;
use Illuminate\Http\Request;

class MailController extends Controller
{

    public function sendMail(CustomMailer $customMailer) {

        // $status = $customMailer
        //     ->to('chockhong1@gmail.com', 'ApuApuApu')
        //     ->subject('Test mail from new implementation v2.m2gv.1')
        //     ->mailer(new TestMail())
        //     ->send()
        // ;

        // Debug information is available
        // dd($status, $customMailer->getUsedProvider(), $customMailer->getDebugLog());

        new TestMail();
    }

}
