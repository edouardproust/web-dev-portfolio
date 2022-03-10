<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ErrorController extends AbstractController
{
    /**
     * @Route("/error", name="app_error")
     */
    public function show(Request $request): Response
    {
        $errorCode = $request->get('exception')->getStatusCode();
        if ($errorCode === 404) {
            return $this->render('error/404.html.twig');
        } else {
            $this->addFlash('danger', 'An error has occured. Error code: ' . $errorCode);
            return $this->redirectToRoute('home');
        }
    }
}
