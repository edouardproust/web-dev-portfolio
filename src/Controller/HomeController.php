<?php

namespace App\Controller;

use DateTime;
use App\Entity\Project;
use App\Repository\UserRepository;
use Bluemmb\Faker\PicsumPhotosProvider;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(UserRepository $userRepo, EntityManagerInterface $entityManager): Response
    {
        $faker = \Faker\Factory::create();
        $faker->addProvider(new PicsumPhotosProvider($faker));
        $project = (new Project)->setCreatedAt(new DateTime('-1 month'));
        $date = $faker->dateTimeBetween($project->getCreatedAt(), '-1 hour');

        return $this->render('home/index.html.twig', [
            'faker' => $faker,
            'date' => $date
        ]);
    }
}
