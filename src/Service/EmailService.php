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
    private $adminOptionService;

    public function __construct(
        MailerInterface $mailer,
        FlashBagInterface $flash,
        AdminOptionService $adminOptionService
    ) {
        $this->mailer = $mailer;
        $this->flash = $flash;
        $this->adminOptionService = $adminOptionService;
    }

    /**
     * Send a notification email to Admin on Author registration
     * @var Author $authorRequest Data from the Author Registration form, reformated into a Author object
     * @return void
     */
    public function sendEmailOnAuthorRegistration(Author $authorRequest)
    {
        $ao = $this->adminOptionService;
        if ($this->adminOptionService->get('NOTIFICATION_NEW_AUTHOR')) {
            $email = (new TemplatedEmail)
                ->to(new Address($ao->get('CONTACT_EMAIL'), $ao->get('CONTACT_NAME')))
                ->from(new Address($ao->get('CONTACT_EMAIL'), $ao->get('CONTACT_NAME')))
                ->subject('New Author registration on ' . $ao->get('SITE_NAME'))
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
        $ao = $this->adminOptionService;
        try {
            $email = (new TemplatedEmail)
                ->to(new Address($ao->get('CONTACT_EMAIL'), $ao->get('CONTACT_NAME')))
                ->from(new Address($data['email'], $data['fullName']))
                ->subject('New contact message from ' . $ao->get('SITE_NAME'))
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
        $ao = $this->adminOptionService;
        try {
            $email = (new TemplatedEmail)
                ->to(new Address($author->getUser()->getEmail(), $author->getFullName()))
                ->from(new Address($ao->get('CONTACT_EMAIL'), $ao->get('SITE_NAME')))
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
