<?php

namespace App\Controller;

use App\Entity\Covers;
use App\Entity\Games;
use App\Entity\Illustrations;
use App\Entity\RecentGames;
use App\Form\EditGameFormType;
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
    #[Route('/', name: 'index')]
    public function index(GamesRepository $gamesRepository, RecentGamesRepository $recentGamesRepository): Response
    {   
        return $this->render('games/index.html.twig', [
            'games' => $gamesRepository->findBy([], ['title' => 'asc']),
            'recentgames' => $recentGamesRepository->findBy([], ['title' => 'asc'])
        ]);
    }

    #[Route('/admin/index', name: 'admin_index')]
    public function adminIndex(GamesRepository $gamesRepository): Response
    {
        return $this->render('/admin/index.html.twig');
    }

    #[Route('/admin/games_list', name: 'admin_games_list')]
    public function list(GamesRepository $gamesRepository)
    {
        return $this->render('admin/games/list.html.twig', [
            'games' => $gamesRepository->findAll(),
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
            // Récupération image
            $covers = $formGame->get('covers')->getData();

            foreach($covers as $cover){
                $folder = 'games'; // Dossier destination
                $file = $pictureService->add($cover, $folder, 300, 300);

                $image = new Covers;
                $image->setName($file);
                $game->addCover($image);
            }
            
            // Récupération utilisateur et génération slug
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

    #[Route('/admin/edit/{id}', name: 'edit_game', methods: ['GET', 'POST'])]
    public function editGame(Request $request, Games $game, GamesRepository $gamesRepository, PictureService $pictureService, SluggerInterface $slugger): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $formGame = $this->createForm(EditGameFormType::class, $game);
        $formGame->handleRequest($request);

        if ($formGame->isSubmitted() && $formGame->isValid()) {

            $covers = $formGame->get('covers')->getData();
            // images = covers

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

            return $this->redirectToRoute('app_games_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/games/edit.html.twig', [
            'games' => $game,
            'formGame' => $formGame->createView()
        ]);
    }

    #[Route('/admin/new_recent', name: 'newRecent')]
    public function newRecentGame(Request $request, EntityManagerInterface $manager, PictureService $pictureService, SluggerInterface $slugger): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $recentGame = new RecentGames();

        $formRecent = $this->createForm(RecentGamesFormType::class, $recentGame);

        $formRecent->handleRequest($request);

        if ($formRecent->isSubmitted() && $formRecent->isValid()) {
            $illustrations = $formRecent->get('illustrations')->getData();

            foreach($illustrations as $illustration){
                $folder = 'illustrations';
                $file = $pictureService->add($illustration, $folder, 300, 300);

                $image = new Illustrations;
                $image->setName($file);
                $recentGame->addIllustration($image);
            }

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
