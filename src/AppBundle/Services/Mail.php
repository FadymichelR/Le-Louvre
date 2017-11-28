<?php

namespace AppBundle\Services;

class Mail
{

    private $mailer;

    public function __construct(\Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public function send($to, $subject, $body)
    {

        $message = \Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom('contact@webbymichel.fr')
            ->setTo($to)
            ->setBody($body,
                'text/html'
            );
        $this->mailer->send($message);

    }

}
