<?php


namespace App\Contracts;

use Illuminate\View\View;

interface CustomMailable
{
    public function build(CustomMailer $customMailer);

    public function mailView() :View;
}
