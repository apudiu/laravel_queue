<?php


namespace App\Contracts;


use phpDocumentor\Reflection\Types\Mixed_;

interface CustomMailer
{

    public function from(string $address, string $name='') : CustomMailer;

    public function to(string $address, string $name = '') : CustomMailer;

    public function subject(string $subject) : CustomMailer;

    public function mailer(CustomMailable $mailer) : CustomMailer;

    public function send() : bool;

    public function getUsedProvider();

    public function getDebugLog() : string;
}
