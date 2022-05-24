<?php

namespace App\Service;

use App\Entity\Author;
use App\Repository\AdminOptionRepository;
use DateTime;
use Symfony\Component\Mime\Address;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

class EmailService
{
    private $mailer;
    private $flash;
    private $aor;
    private $siteEmail;
    private $siteName;

    public function __construct(
        MailerInterface $mailer,
        FlashBagInterface $flash,
        AdminOptionRepository $adminOptionRepository
    ) {
        $this->mailer = $mailer;
        $this->flash = $flash;
        $this->aor = $adminOptionRepository;
        $this->siteEmail = $adminOptionRepository->get('CONTACT_EMAIL')->getValue();
        $this->siteName = $adminOptionRepository->get('CONTACT_NAME')->getValue();
    }

    /**
     * Send a notification email to Admin on Author registration
     * @var Author $authorRequest Data from the Author Registration form, reformated into a Author object
     * @return void
     */
    public function sendEmailOnAuthorRegistration(Author $authorRequest)
    {
        if ($this->aor->get('NOTIFICATION_NEW_AUTHOR')->getValue()) {
            $email = (new TemplatedEmail)
                ->to(new Address($this->siteEmail, $this->siteName))
                ->from(new Address($this->siteEmail, $this->siteName))
                ->subject('New Author registration on ' . $this->siteName)
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
        $siteAdress = new Address($this->siteEmail, $this->siteName);
        $data['dateTime'] = new DateTime('now');
        try {
            $email = (new TemplatedEmail)
                ->to($siteAdress)
                ->from($siteAdress)
                ->subject('New contact message from ' . $this->siteName)
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
                ->from(new Address(
                    $this->siteEmail,
                    $this->siteName
                ))
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
