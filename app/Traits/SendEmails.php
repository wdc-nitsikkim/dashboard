<?php

namespace App\Traits;

use Illuminate\Support\Facades\Log;

use App\CustomHelper;

/**
 * Default functions to send emails using sendgrid-php api client
 */
trait SendEmails
{
    protected function getDefaultConfig()
    {
        $email = new \SendGrid\Mail\Mail();
        $email->setFrom(config('mail.sendgrid.from_address'), config('mail.from.name'));
        $email->setTemplateId(
            new \SendGrid\Mail\TemplateId(config('mail.sendgrid.default_template'))
        );
        return $email;
    }

    public function sendSendgridEmail($email)
    {
        $sendgrid = new \SendGrid(config('mail.sendgrid.api_key'));
        try {
            $response = $sendgrid->send($email);
            $statusCode = $response->statusCode();
            if ($statusCode >= 200 && $statusCode <= 220) {
                Log::info('Email sent.', [$statusCode]);
                return true;
            } else {
                var_dump($response);
                Log::alert('Email not sent.', [$statusCode, $response]);
                return false;
            }
        } catch (\Exception $e) {
            Log::debug('Failed to send email.', [$statusCode, $e->getMessage()]);
            return false;
        }
    }

    /**
     * send email verification link
     *
     * @param string $to  receivers email address
     * @param string $to_name  name of receiver
     * @param string $link  primary action btn link
     * @return boolean
     */
    public function sendEmailVerification($to, $to_name, $link)
    {
        $subject = 'Verify email address';

        $email = $this->getDefaultConfig();
        $email->setSubject($subject);
        $email->addTo($to, $to_name);

        $email->addDynamicTemplateDatas([
            'subject' => $subject,
            'title' => 'NIT Sikkim',
            'name' => $to_name,
            'action_btn_link' => $link,
            'action_btn_text' => 'Verify Email',
            'action_btn_bg' => '#2ab27b',
            'primary_text' => [
                ['text' => 'Click the button below to verify your email address']
            ],
            'secondary_text' => [
                ['text' => 'This email can be safely ignored if you haven\'t registered.']
            ],
            'signed_by' => 'WDC, NIT Sikkim'
        ]);

        return $this->sendSendgridEmail($email);
    }

    /**
     * send password reset link
     *
     * @param string $to  receivers email address
     * @param string $to_name  name of receiver
     * @param string $link  primary action btn link
     * @return boolean
     */
    public function sendPasswordReset($to, $to_name, $link)
    {
        $subject = 'Reset account password';

        $email = $this->getDefaultConfig();
        $email->setSubject($subject);
        $email->addTo($to, $to_name);

        $email->addDynamicTemplateDatas([
            'subject' => $subject,
            'title' => 'NIT Sikkim',
            'name' => $to_name,
            'action_btn_link' => $link,
            'action_btn_text' => 'Reset Password',
            'action_btn_bg' => '#bf5329',
            'primary_text' => [
                ['text' => 'Click the button below to reset your account password']
            ],
            'secondary_text' => [
                ['text' => 'Do not share this link with anyone!'],
                ['text' => 'This email can be safely ignored if you did not request a password reset.']
            ],
            'signed_by' => 'WDC, NIT Sikkim'
        ]);

        return $this->sendSendgridEmail($email);
    }
}
