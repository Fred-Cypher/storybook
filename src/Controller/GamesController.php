<?php

namespace App\Controller;

use App\Entity\Games;
use App\Entity\RecentGames;
use App\Form\GamesFormType;
use App\Form\RecentGamesFormType;
use App\Repository\GamesRepository;
use App\Repository\RecentGamesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/games', name: 'app_games_')]
class GamesController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(GamesRepository $gamesRepository, RecentGamesRepository $recentGamesRepository): Response
    {   
        return $this->render('games/index.html.twig', [
            'games' => $gamesRepository->findBy([], ['title' => 'asc']),
            'recentgames' => $recentGamesRepository->findBy([], ['title' => 'asc'])
        ]);
    }

    #[Route('/admin/new', 'new')]
    public function newGame(Request $request, EntityManagerInterface $manager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $game = new Games();

        $formGame = $this->createForm(GamesFormType::class, $game);

        $formGame->handleRequest($request);

        if($formGame->isSubmitted() && $formGame->isValid()){
            $manager->persist($game);
            $manager->flush();

            $this->addFlash('success', 'Le jeu a bien été enregistré');

            return $this->redirectToRoute('app_admin_index');
        }

        return $this->render('admin/games/new.html.twig', [
            'formGame' => $formGame->createView()
        ]);
    }

    #[Route('/admin/new_recent', 'newRecent')]
    public function newRecentGame(Request $request, EntityManagerInterface $manager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $game = new RecentGames();

        $formRecent = $this->createForm(RecentGamesFormType::class, $game);

        $formRecent->handleRequest($request);

        if ($formRecent->isSubmitted() && $formRecent->isValid()) {
            $manager->persist($game);
            $manager->flush();

            $this->addFlash('success', 'Le jeu récent a bien été enregistré');

            return $this->redirectToRoute('app_admin_index');
        }

        return $this->render('admin/recentGames/new.html.twig', [
            'formRecent' => $formRecent->createView()
        ]);
    }
} 
