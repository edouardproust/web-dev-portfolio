<?php

namespace App\Service;

use App\Config;
use Symfony\Component\Mime\Address;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Mailer\MailerInterface;

class ContactService
{

    /** @var MailerInterface */
    private $mailer;

    /** @var FlashBagInterface */
    private $flash;

    public function __construct(MailerInterface $mailer, FlashBagInterface $flash)
    {
        $this->mailer = $mailer;
        $this->flash = $flash;
    }

    /**
     * Send an notification email to Admin on contact form submission
     * @param array $data FromTo informations
     * @return void 
     */
    public function sendEmailNotif(array $data)
    {
        try {
            $email = (new TemplatedEmail)
                ->to(new Address(Config::CONTACT_EMAIL, Config::CONTACT_NAME))
                ->from(new Address($data['email'], $data['fullName']))
                ->subject('New contact message from ' . Config::SITE_NAME)
                ->htmlTemplate('email/contact.html.twig')
                ->context([
                    'contactMessage' => $data
                ]);
            $this->mailer->send($email);
            $this->flash->add('success', 'Your message has been sent. I will answer you as soon as possible.');
        } catch (\Exception $e) {
            $this->flash->add('danger', 'Failed to send your message. Error: ' . $e->getMessage());
        }
    }
}
