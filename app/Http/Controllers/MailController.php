<?php

namespace App\Http\Controllers;

use App\Jobs\SendTestMail;
use Exception;
use Illuminate\Http\Request;

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

    public function sendMailFromApi(Request $request) {

        // Here not using laravel validation..

        $times = $request->post('times');

        if (empty($times) || $times < 1 || $times > 10) {

            $data = [
                'status' => false,
                'message' => "'times' is required and must be between 1 to 10"
            ];

            return response($data,400);
        }



        for ($i = 0; $i < $times; $i++) {

            SendTestMail::dispatch();

        }

        $data = [
            'status' => true,
            'message' => "{$times} mails(s) has been sent to queue"
        ];

        return response($data);
    }

}
