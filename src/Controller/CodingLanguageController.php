<?php

namespace App\Controller;

use App\Repository\CodingLanguageRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CodingLanguageController extends AbstractController
{
    /** @var CodingLanguageRepository */
    private $codingLanguageRepository;

    public function __construct(
        CodingLanguageRepository $codingLanguageRepository
    ) {
        $this->codingLanguageRepository = $codingLanguageRepository;
    }

    /**
     * @Route("/projects/language/{slug}", name="coding_language")
     */ 
    public function index($slug): Response
    {
        return $this->render('coding_language/index.html.twig', [
            'codingLanguage' => $this->codingLanguageRepository->findOneBy([
                'slug' => $slug
            ])
        ]);
    }
}
