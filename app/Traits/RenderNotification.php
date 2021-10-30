<?php

namespace App\Traits;

use Illuminate\Mail\Markdown;
use Illuminate\Notifications\AnonymousNotifiable;

/**
 * Adds renderable capability to notification
 * @see https://dev.to/mratiebatie/previewing-markdown-notifications-directly-in-your-browser-2g3n
 */
trait RenderNotification
{
    /**
     * Get HTML representation of notification to display in browsers for testing
     * or for other purpose
     * This method is already present in Mailable class
     *
     * @param string $email  (optional)
     * @return string  HTML respresentation of notification
     */
    public function render($email = null)
    {
        $anonNotify = new AnonymousNotifiable;
        $notification = $this->toMail($anonNotify->route('mail', [$email ?? 'ankushyadav9302@gmail.com']));
        $markdown = new Markdown(view(), config('mail.markdown'));

        return $markdown->render($notification->markdown, $notification->data());
    }
}
