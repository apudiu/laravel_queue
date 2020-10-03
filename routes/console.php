<?php

use App\Jobs\SendTestMail;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/**
 * Of course this is the simplest command, we know that we can do much more elegant
 * things with different implementation
 */
Artisan::command('sendmail', function () {
    SendTestMail::dispatch();
    echo "Mail placed on the queue\n";
})->purpose('Queues (test email) for sending/processing');
