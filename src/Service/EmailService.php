<?php

namespace App\Service;

use App\Config;
use App\Entity\Author;
use Symfony\Component\Mime\Address;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

class EmailService
{
    private $mailer;
    private $flash;

    public function __construct(
        MailerInterface $mailer,
        FlashBagInterface $flash
    ) {
        $this->mailer = $mailer;
        $this->flash = $flash;
    }

    /**
     * Send a notification email to Admin on Author registration
     * @var Author $authorRequest Data from the Author Registration form, reformated into a Author object
     * @return void
     */
    public function sendEmailOnAuthorRegistration(Author $authorRequest)
    {
        if (Config::NOTIFICATION_NEW_AUTHOR) {
            $email = (new TemplatedEmail)
                ->to(new Address(Config::CONTACT_EMAIL, Config::CONTACT_NAME))
                ->from(new Address(Config::CONTACT_EMAIL, Config::CONTACT_NAME))
                ->subject('New Author registration on ' . Config::SITE_NAME)
                ->htmlTemplate('email/author_registration.html.twig')
                ->context([
                    'authorRequest' => $authorRequest
                ]);
            $this->mailer->send($email);
        }
    }

    /**
     * Send an notification email to Admin on contact form submission
     * @param array $data FromTo informations
     * @return void
     */
    public function sendEmailOnContactSubmit(array $data)
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

    public function sendNotifOnAuthorApproval(Author $author)
    {
        try {
            $email = (new TemplatedEmail)
                ->to(new Address($author->getUser()->getEmail(), $author->getFullName()))
                ->from(new Address(Config::CONTACT_EMAIL, Config::SITE_NAME))
                ->subject('Author registration accepted')
                ->htmlTemplate('email/author_approval.html.twig')
                ->context([
                    'author' => $author
                ]);
            $this->mailer->send($email);
            $this->flash->add(
                'success',
                'The author request has been approved. A confirmation email has been sent to him/her.'
            );
        } catch (\Exception $e) {
            $this->flash->add(
                'danger',
                'Failed to send confirmation email to the author. Error: ' . $e->getMessage()
            );
        }
    }
}
