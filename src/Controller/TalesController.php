<?php

namespace App\Controller;

use App\Entity\Tales;
use App\Repository\TalesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use tidy;

#[Route('/tales', name: 'app_tales_')]
class TalesController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(TalesRepository $talesRepository): Response
    {
        return $this->render('tales/index.html.twig', [
            'tales' => $talesRepository->findBy([], ['id' => 'asc']),
        ]);
    }

    #[Route('/{slug}', name:'show', methods: ['GET'])]
    public function show(Tales $tales): Response
    {
        return $this->render('tales/show.html.twig', [
            'tales' => $tales,
        ]);
    }
}
