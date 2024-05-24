<?php

namespace App\Controller;

use App\Repository\ResumeRepository;
use App\Repository\TalesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    // Affichage de ma page d'accueil du site
    #[Route('/', name: 'app_main', methods: ['GET'])]
    public function index(TalesRepository $talesRepository, ResumeRepository $resumeRepository): Response
    {
        $tales = $talesRepository->findAll();
        $resumes = $resumeRepository->findAll();

        return $this->render('main/index.html.twig', [
            'tales' => $tales,
            'resumes' => $resumes
        ]);
    }

    // Affichage des images de background
    #[Route('/backgrounds', name: 'app_backgrounds')]
    public function backgrounds()
    {
        return $this->render('main/backgrounds.html.twig');
    }

    // Page pour faire des tests d'affichage pendant le développement
    #[Route('/tests', name: 'app_tests')]
    public function tests()
    {
        return $this->render('main/tests.html.twig');
    }
}