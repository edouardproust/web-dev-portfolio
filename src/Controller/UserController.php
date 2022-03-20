<?php

namespace App\Controller;

use App\Config;
use App\Form\UserEditType;
use App\Service\EasyAdminService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserController extends AbstractController
{
    private $entityManager;
    private $hasher;
    private $easyAdminService;

    public function __construct(
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $hasher,
        EasyAdminService $easyAdminService
    ) {
        $this->entityManager = $entityManager;
        $this->hasher = $hasher;
        $this->easyAdminService = $easyAdminService;
    }

    /**
     * @Route("/account", name="user_show")
     */
    public function show(Request $request): Response
    {
        if (!$this->getUser()) {
            $this->addFlash('danger', 'You must be connected to access this page.');
            return $this->redirectToRoute('app_login');
        }
        if ($this->easyAdminService->isAdminPanelAccessGranted()) {
            return $this->redirectToRoute('admin');
        }

        $password = $this->getUser()->getPassword();
        $form = $this->createForm(UserEditType::class, $this->getUser());
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            // password handle
            if (!$user->getPassword()) {
                $user->setPassword($password);
            } else {
                $user->setPassword(
                    $this->hasher->hashPassword($user, $user->getPassword())
                );
            }
            // push to database
            $this->entityManager->persist($user);
            $this->entityManager->flush();
            $this->addFlash('success', 'Account updated.');
        }
        return $this->render('user/show.html.twig', [
            'userForm' => $form->createView()
        ]);
    }
}
