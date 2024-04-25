<?php

namespace App\Controller;

use App\Entity\Covers;
use App\Entity\Games;
use App\Entity\Illustrations;
use App\Entity\RecentGames;
use App\Form\EditGameFormType;
use App\Form\EditRecentGameFormType;
use App\Form\GamesFormType;
use App\Form\RecentGamesFormType;
use App\Repository\GamesRepository;
use App\Repository\RecentGamesRepository;
use App\Service\PictureService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/games', name: 'app_games_')]
class GamesController extends AbstractController
{
    // Affichage de la page d'index avec les cartes de présentation des jeux anciens et récents 
    #[Route('/', name: 'index')]
    public function index(GamesRepository $gamesRepository, RecentGamesRepository $recentGamesRepository): Response
    {   
        return $this->render('games/index.html.twig', [
            'games' => $gamesRepository->findBy([], ['title' => 'asc']),
            'recentgames' => $recentGamesRepository->findBy([], ['title' => 'asc'])
        ]);
    }

    // Affichage de liste des jeux anciens à modifier / supprimer
    #[Route('/admin/games_list', name: 'admin_games_list')]
    public function list(GamesRepository $gamesRepository)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        return $this->render('admin/games/list.html.twig', [
            'games' => $gamesRepository->findAll(),
        ]);
    }

    // Affichage de la page d'enregistrement d'un nouveau jeu ancien
    #[Route('/admin/new', name: 'new')]
    public function newGame(Request $request, EntityManagerInterface $manager, PictureService $pictureService, SluggerInterface $slugger): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        // Création d'un nouveau jeu 
        $game = new Games();
        // Création du formulaire
        $formGame = $this->createForm(GamesFormType::class, $game);
        // Traitement de la requête du formulaire
        $formGame->handleRequest($request);

        // Vérification que le formulaire est soumis et valide
        if($formGame->isSubmitted() && $formGame->isValid()){
            // Récupération image et ajout dans le dossier 'games'
            $covers = $formGame->get('covers')->getData();

            foreach($covers as $cover){
                $folder = 'games'; // Dossier destination
                $file = $pictureService->add($cover, $folder, 300, 300);

                $image = new Covers;
                $image->setName($file);
                $game->addCover($image);
            }
            
            // Récupération utilisateur connecté et génération slug
            $game->setUser($this->getUser());
            $slug = $slugger->slug($game->getTitle());
            $game->setSlug($slug);

            $manager->persist($game);
            $manager->flush();

            $this->addFlash('success', 'Le jeu a bien été enregistré');

            return $this->redirectToRoute('app_admin_index');
        }

        return $this->render('admin/games/new.html.twig', [
            'formGame' => $formGame->createView()
        ]);
    }

    // Suppression d'un jeu ancien
    #[Route('/admin/delete/{id}', name: 'delete_game', methods: ['POST'])]
    public function deleteGame(Games $game, Request $request, GamesRepository $gamesRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', $game);

        if($this->isCsrfTokenValid('delete' . $game->getId(), $request->request->get('_token'))) {
            $gamesRepository->remove($game, true);
        }
        $this->addFlash('success', 'Le jeu a bien été supprimé');

        return $this->render('admin/index.html.twig');
    }

    // Suppression des images du jeu ancien du dossier 'games'
    #[Route('/admin/delete/cover/{id}', name: 'delete_cover', methods: ['DELETE'])]
    public function deleteCover(Covers $cover, Request $request, EntityManagerInterface $manager, PictureService $pictureService): JsonResponse
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        // Récupération requête
        $data = json_decode($request->getContent(), true);

        if($this->isCsrfTokenValid('delete' . $cover->getId(), $data['_token'])){
            // Token valide
            // Récupération nom image
            $coverName = $cover->getName();

            if($pictureService->delete($coverName, 'games', 300, 300)){
                $manager->remove($cover);
                $manager->flush();

                return new JsonResponse(['success' => true], 200);
            }
            return new JsonResponse(['error' => 'Erreur de suppression'], 400);
        }

        return new JsonResponse(['error' => 'Token invalide'], 400);
    }

    // Affichage de la page de modification d'un jeu ancien
    #[Route('/admin/edit/{id}', name: 'edit_game', methods: ['GET', 'POST'])]
    public function editGame(Request $request, Games $game, GamesRepository $gamesRepository, PictureService $pictureService, SluggerInterface $slugger): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $formGame = $this->createForm(EditGameFormType::class, $game);
        $formGame->handleRequest($request);

        // Vérification que le formulaire est soumis et valide
        if ($formGame->isSubmitted() && $formGame->isValid()) {
            // Récupération image et ajout dans le dossier 'games'
            $covers = $formGame->get('covers')->getData();

            foreach ($covers as $cover) {
                $folder = 'games';
                $file = $pictureService->add($cover, $folder, 300, 300);

                $image = new Covers;
                $image->setName($file);
                $game->addCover($image);
            }

            $game->setUser($this->getUser());
            $slug = $slugger->slug($game->getTitle());
            $game->setSlug($slug);
            $game->setUpdatedAt(new \DateTimeImmutable());
            $gamesRepository->save($game, true);

            $this->addFlash('info', 'Le jeu a bien été modifié');

            return $this->redirectToRoute('app_admin_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/games/edit.html.twig', [
            'games' => $game,
            'formGame' => $formGame->createView()
        ]);
    }

    // Affichage de liste des jeux récents à modifier / supprimer
    #[Route('/admin/recent_games_list', name: 'admin_recent_games_list')]
    public function listRecent(RecentGamesRepository $recentGamesRepository)
    {
        return $this->render('admin/recentGames/list.html.twig', [
            'recentGames' => $recentGamesRepository->findAll(),
        ]);
    }

    // Affichage de la page d'enregistrement d'un nouveau jeu récent
    #[Route('/admin/new_recent', name: 'new_recent')]
    public function newRecentGame(Request $request, EntityManagerInterface $manager, PictureService $pictureService, SluggerInterface $slugger): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $recentGame = new RecentGames();

        $formRecent = $this->createForm(RecentGamesFormType::class, $recentGame);

        $formRecent->handleRequest($request);

        // Vérification que le formulaire est soumis et valide
        if ($formRecent->isSubmitted() && $formRecent->isValid()) {
            // Récupération image et ajout dans le dossier 'illustrations'
            $illustrations = $formRecent->get('illustrations')->getData();

            foreach($illustrations as $illustration){
                $folder = 'illustrations'; // Dossier destination
                $file = $pictureService->add($illustration, $folder, 300, 300);

                $image = new Illustrations;
                $image->setName($file);
                $recentGame->addIllustration($image);
            }

            // Récupération utilisateur connecté et génération slug
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

    // Suppression d'un jeu récent
    #[Route('/admin/delete_recent/{id}', name: 'delete_recent_game', methods: ['POST'])]
    public function deleteRecentGame(RecentGames $recentGame, Request $request, RecentGamesRepository $recentGamesRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', $recentGame);

        if ($this->isCsrfTokenValid('delete' . $recentGame->getId(), $request->request->get('_token'))) {
            $recentGamesRepository->remove($recentGame, true);
        }
        $this->addFlash('success', 'Le jeu a bien été supprimé');

        return $this->render('admin/index.html.twig');
    }

    // Suppression des images d'un jeu récent du dossier 'illustrations'
    #[Route('/admin/delete/illustration/{id}', name: 'delete_illustration', methods: ['DELETE'])]
    public function deleteIllustration(Illustrations $illustration, Request $request, EntityManagerInterface $manager, PictureService $pictureService): JsonResponse
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        // Récupération requête
        $data = json_decode($request->getContent(), true);

        if ($this->isCsrfTokenValid('delete' . $illustration->getId(), $data['_token'])) {
            // Token valide
            // Récupération nom image
            $illustrationName = $illustration->getName();

            if ($pictureService->delete($illustrationName, 'illustrations', 300, 300)) {
                $manager->remove($illustration);
                $manager->flush();

                return new JsonResponse(['success' => true], 200);
            }
            return new JsonResponse(['error' => 'Erreur de suppression'], 400);
        }

        return new JsonResponse(['error' => 'Token invalide'], 400);
    }

    // Affichage de la page de modification d'un jeu récent
    #[Route('/admin/edit_recent/{id}', name: 'edit_recent_game', methods: ['GET', 'POST'])]
    public function editRecentGame(Request $request, RecentGames $recentGame, RecentGamesRepository $recentGamesRepository, PictureService $pictureService, SluggerInterface $slugger): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $formRecentGame = $this->createForm(EditRecentGameFormType::class, $recentGame);
        $formRecentGame->handleRequest($request);

        // Vérification que le formulaire est soumis et valide
        if ($formRecentGame->isSubmitted() && $formRecentGame->isValid()) {
            // Récupération image et ajout dans la dossier 'illustrations'
            $illustrations = $formRecentGame->get('illustrations')->getData();
            
            foreach ($illustrations as $illustration) {
                $folder = 'illustrations';
                $file = $pictureService->add($illustration, $folder, 300, 300);

                $image = new Illustrations;
                $image->setName($file);
                $recentGame->addIllustration($image);
            }

            $recentGame->setUser($this->getUser());
            $slug = $slugger->slug($recentGame->getTitle());
            $recentGame->setSlug($slug);
            $recentGame->setUpdatedAt(new \DateTimeImmutable());
            $recentGamesRepository->save($recentGame, true);

            $this->addFlash('info', 'Le jeu a bien été modifié');

            return $this->redirectToRoute('app_admin_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/recentGames/edit.html.twig', [
            'recentGames' => $recentGame,
            'formRecentGame' => $formRecentGame->createView()
        ]);
    }

} 
