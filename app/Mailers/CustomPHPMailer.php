<?php


namespace App\Mailers;


use App\Contracts\CustomMailable;
use App\Contracts\CustomMailer;
use App\Exceptions\CustomPHPMailerException;
use Illuminate\View\View;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception as PHPMailerException;
use PHPMailer\PHPMailer\SMTP;

class CustomPHPMailer implements CustomMailer
{
    private
        $mailer,
        $providers,
        $sendStatus = false,
        $debugLog='',
        $usedProvider=[]
    ;


    public function __construct(PHPMailer $phpMailer)
    {
        // Get mailer implementation resolved from container
        $this->mailer = $phpMailer;

        // get all available providers
        $this->providers = config('mail.mailProviders.providers');

        // setting defaults
        $this->mailer->setFrom(config('mail.from.address'), config('mail.from.name'));

        // Debugging
        $this->captureDebugLog(SMTP::DEBUG_CLIENT);
    }

    /**
     * @param string $address
     * @param string $name
     * @return CustomMailer
     * @throws PHPMailerException
     */
    public function from(string $address, string $name = '') : CustomMailer
    {
        $this->mailer->setFrom($address, $name);

        return $this;
    }

    /**
     * @param string $address
     * @param string $name
     * @return CustomPHPMailer
     * @throws PHPMailerException
     */
    public function to(string $address, string $name = '') : CustomMailer
    {
        $this->mailer->addAddress($address, $name);

        return $this;
    }

    /**
     * @param string $subject
     * @return CustomMailer
     */
    public function subject(string $subject) : CustomMailer
    {
        $this->mailer->Subject =$subject;

        return $this;
    }

    /**
     * @param $mailer
     * @return mixed
     * @throws \Throwable
     */
    public function mailer(CustomMailable $mailer) : CustomMailer
    {

        $mailMessage = $mailer->build()->render();

        $this->mailer->MsgHTML($mailMessage);

        return $this;
    }

    /**
     * @return bool
     * @throws PHPMailerException|CustomPHPMailerException
     */
    public function send() : bool
    {
        // Apply generic configs
        $this->applyMailConfig();

        // if (empty($this->mailer->Username)) {
        //     throw new CustomPHPMailerException('Mail server credentials not set!');
        // }


        // send the mail
        do {

            // use provider
            $provider = $this->getMailProvider();

            if (!$provider) {

                // throwing exception will break the loop

                if (count($this->providers)) {
                    throw new CustomPHPMailerException('No mail providers available');
                }

                throw new CustomPHPMailerException('Mail send failed with all providers!');
            }

            $this->setCredentials($provider);


            $sendStatus = $this->mailer->send();
            $this->sendStatus = $sendStatus;

            // store last used provider when sending successful
            if ($this->sendStatus) {

                $this->usedProvider = $provider;
            }

        } while (!$sendStatus);

        return $this->sendStatus;

    }

    public function getUsedProvider()
    {
        return $this->usedProvider;
    }

    public function getDebugLog() :string
    {
        return $this->debugLog;
    }

    private function applyMailConfig() : void
    {
        // Apply some generic config
        $this->mailer->isSMTP();
        $this->mailer->CharSet = 'utf-8';
        $this->mailer->SMTPAuth = true;

    }

    /**
     * Get single provider from providers
     * In every call this will return next provider (next one of that returned in previous call)
     *
     * This will return false if all providers are already returned
     *
     * @return array|false
     */
    private function getMailProvider() {

        // Get current (cursor) provider
        $provider = current($this->providers);

        // forward the cursor
        next($this->providers);

        return $provider;

    }

    private function captureDebugLog($debugLevel, $lineBreak="\n") :void
    {
        if ($debugLevel) {

            $this->mailer->SMTPDebug = $debugLevel;
            $this->mailer->Debugoutput = function ($str, $level) use ($lineBreak) {

                $this->debugLog .= "[level]: {$level} > [message]: {$str}{$lineBreak}";
            };
        }
    }

    private function getDebugInfo() :array
    {
        return [
            'isError' => $this->mailer->isError(),
            'errorMsg'=> $this->mailer->ErrorInfo
        ];
    }

    /**
     * @param array $config
     * @return CustomMailer
     */
    private function setCredentials(array $config) : CustomMailer
    {
        $keyMapping = [
            'host' => 'Host',
            'port' => 'Port',
            'user' => 'Username',
            'pass' => 'Password',
        ];

        foreach ($config as $key => $value) {

            $mappedKey = $keyMapping[$key];

            $this->mailer->$mappedKey = $value;
        }

        return $this;
    }
}
