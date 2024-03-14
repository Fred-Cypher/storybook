<?php

namespace App\Controller;

use App\Repository\TalesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'app_main', methods: ['GET'])]
    public function index(TalesRepository $talesRepository): Response
    {
        $tales = $talesRepository->findAll();

        return $this->render('main/index.html.twig', [
            'tales' => $tales
        ]);
    }
}