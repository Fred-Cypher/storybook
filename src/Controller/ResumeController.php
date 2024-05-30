<?php

namespace App\Controller;

use App\Entity\Portraits;
use App\Entity\Resume;
use App\Form\EditResumeFormType;
use App\Form\ResumeFormType;
use App\Repository\ResumeRepository;
use App\Service\PictureService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/resume', name: 'app_resume_')]
class ResumeController extends AbstractController
{
    // Affichage des images de la page d'accueil et des textes
    #[Route('/admin/portraits_list', name: 'admin_portraits_list')]
    public function listPortraits(ResumeRepository $resumeRepository)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        return $this->render('admin/resume/list.html.twig', [
            'resume' => $resumeRepository->findAll(),
        ]);
    }

    // Affichage de la page d'enregistrement de la page d'accueil
    #[Route('/admin/new', name: 'new')]
    public function new(Request $request, EntityManagerInterface $manager, PictureService $pictureService, SluggerInterface $slugger): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $resume = new Resume();

        $formResume = $this->createForm(ResumeFormType::class, $resume);

        $formResume->handleRequest($request);

        if($formResume->isSubmitted() && $formResume->isValid()){
            // Récupération images générées via une IA et ajout dans le dossier 'portraitia'
            $portraits = $formResume->get('portraits')->getData();

            foreach($portraits as $portrait){
                $folder = 'portraitia';
                $file = $pictureService->add($portrait, $folder, 300, 300);

                $image = new Portraits;
                $image->setName($file);
                $resume->addPortrait($image);
            }

            // Récupération utilisateur et génération slug
            $resume->setUser($this->getUser());
            $slug = $slugger->slug($resume->getTitle());
            $resume->setSlug($slug);

            $manager->persist($resume);
            $manager->flush();

            $this->addFlash('success', 'La page d\'accueil a bien été enregistrée');

            return $this->redirectToRoute('app_admin_index');
        }

        return $this->render('admin/resume/new.html.twig', [
            'formResume' => $formResume->createView()
        ]);

    } 
    
    // Suppression d'une image du portrait
    #[Route('/admin/delete/portrait/{id}', name: 'delete_portrait', methods: ['DELETE'])]
    public function deleteDrawing(Portraits $portrait, Request $request, EntityManagerInterface $manager, PictureService $pictureService): JsonResponse
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        // Récupération du contenu de la requête
        $data = json_decode($request->getContent(), true);

        if ($this->isCsrfTokenValid('delete' . $portrait->getId(), $data['_token'])) {
            // Token valide, récupération du nom de l'image
            $portraitName = $portrait->getName();

            if ($pictureService->delete($portraitName, 'portraitia', 300, 300)) {
                // Suppression de l'image 
                $manager->remove($portrait);
                $manager->flush();

                return new JsonResponse(['success' => true], 200);
            }
            return new JsonResponse(['error' => 'Erreur de suppression'], 400);
        }
        return new JsonResponse(['error' => ('Token invalide')], 400);
    }

    // Affichage de la page de modification de la page de présentation
    #[Route('/admin/edit/{id}', name: 'edit_resume', methods: ['GET', 'POST'])]
    public function editResume(Request $request, Resume $resume, ResumeRepository $resumeRepository, PictureService $pictureService, SluggerInterface $slugger): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $formResume = $this->createForm(EditResumeFormType::class, $resume);
        $formResume->handleRequest($request);

        // Vérification que le formulaire est soumis et valide
        if($formResume->isSubmitted() && $formResume->isValid()){
            // Récupération image et ajout dans le dossier 'portraitia'
            $portraits = $formResume->get('portraits')->getData();

            foreach ($portraits as $portrait) {
                $folder = 'portraitia';
                $file = $pictureService->add($portrait, $folder, 300, 300);

                $image = new Portraits;
                $image->setName($file);
                $resume->addPortrait($image);
            }

            $resume->setUser($this->getUser());
            $slug = $slugger->slug($resume->getTitle());
            $resume->setSlug($slug);
            $resume->setUpdatedAt(new \DateTimeImmutable());
            $resumeRepository->save($resume, true);

            $this->addFlash('info', 'La page d\'accueil a bien été modifiée');

            return $this->redirectToRoute('app_admin_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/resume/edit.html.twig', [
            'resume' => $resume,
            'formResume' => $formResume->createView()
        ]);
    }
}
