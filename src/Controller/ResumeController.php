<?php

namespace App\Controller;

use App\Repository\ResumeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/resume', name: 'app_resume')]
class ResumeController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(ResumeRepository $resumeRepository): Response
    {
        return $this->render('resume/index.html.twig', [
            'resume' => $resumeRepository->findAll(),
        ]);
    }

}
