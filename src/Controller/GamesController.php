<?php

namespace App\Controller;

use App\Entity\Covers;
use App\Entity\Games;
use App\Entity\RecentGames;
use App\Form\EditGameFormType;
use App\Form\GamesFormType;
use App\Form\RecentGamesFormType;
use App\Repository\GamesRepository;
use App\Repository\RecentGamesRepository;
use App\Service\PictureService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

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

    #[Route('/admin/new', name: 'new')]
    public function newGame(Request $request, EntityManagerInterface $manager, PictureService $pictureService, SluggerInterface $slugger): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $game = new Games();

        $formGame = $this->createForm(GamesFormType::class, $game);

        $formGame->handleRequest($request);

        if($formGame->isSubmitted() && $formGame->isValid()){
            $covers = $formGame->get('covers')->getData();
            // images = covers

            foreach($covers as $cover){
                $folder = 'games';
                $file = $pictureService->add($cover, $folder, 300, 300);

                $image = new Covers;
                $image->setName($file);
                $game->addCover($image);
            }
            

            $game->setUser($this->getUser());
            $slug = $slugger->slug($game->getTitle());
            $game->setSlug($slug);
            /* $game->setCover($fichier);*/
            $manager->persist($game);
            $manager->flush();

            $this->addFlash('success', 'Le jeu a bien été enregistré');

            return $this->redirectToRoute('app_admin_index');
        }

        return $this->render('admin/games/new.html.twig', [
            'formGame' => $formGame->createView()
        ]);
    }

    #[Route('/{id}', name: 'show_game', methods: ['GET'])]
    public function showGame(Games $games): Response
    {
        return $this->render('games/_show.html.twig', [
            'game' => $games
        ]);
    }

    #[Route('admin/edit/{id}', name: 'edit_game', methods: ['GET', 'POST'])]
    public function editGame(Request $request, Games $games, GamesRepository $gamesRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $formGame = $this->createForm(EditGameFormType::class, $games);
        $formGame->handleRequest($request);

        if ($formGame->isSubmitted() && $formGame->isValid()) {
            $games->setUser($this->getUser());
            $games->setUpdatedAt(new \DateTimeImmutable());
            $gamesRepository->save($games, true);

            $this->addFlash('info', 'Le jeu a bien été modifié');

            return $this->redirectToRoute('app_games_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/games/edit.html.twig', [
            'games' => $games,
            'formGame' => $formGame
        ]);
    }

    #[Route('/admin/new_recent', name: 'newRecent')]
    public function newRecentGame(Request $request, EntityManagerInterface $manager, SluggerInterface $slugger): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $recentGame = new RecentGames();

        $formRecent = $this->createForm(RecentGamesFormType::class, $recentGame);

        $formRecent->handleRequest($request);

        if ($formRecent->isSubmitted() && $formRecent->isValid()) {
            $recentGame->setUser($this->getUser());
            $slug = $slugger->slug($recentGame->getTitle());
            $recentGame->setSlug($slug);
            $manager->persist($recentGame);
            $manager->flush();

            $this->addFlash('success', 'Le jeu récent a bien été enregistré');

            return $this->redirectToRoute('app_admin_index');
        }

        return $this->render('admin/recentGames/new.html.twig', [
            'formRecent' => $formRecent->createView()
        ]);
    }

} 
