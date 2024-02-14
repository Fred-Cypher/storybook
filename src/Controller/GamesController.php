<?php

namespace App\Controller;

use App\Repository\GamesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/games', name: 'app_games_')]
class GamesController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(GamesRepository $gamesRepository): Response
    {   
        return $this->render('games/index.html.twig', [
            'games' => $gamesRepository->findBy([], ['title' => 'asc'])
        ]);
    }
} 
