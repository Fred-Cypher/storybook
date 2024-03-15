<?php

namespace App\Controller;

use App\Entity\Drawings;
use App\Entity\Tales;
use App\Form\EditTaleFormType;
use App\Form\TalesFormType;
use App\Repository\TalesRepository;
use App\Service\PictureService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

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

    #[Route('/admin/tales_list', name: 'admin_tales_list')]
    public function listTales(TalesRepository $talesRepository)
    {
        return $this->render('admin/tales/list.html.twig', [
            'tales' => $talesRepository->findAll(),
        ]);
    }

    #[Route('/admin/new', name: 'new')]
    public function new(Request $request, EntityManagerInterface $manager, PictureService $pictureService, SluggerInterface $slugger): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $tale = new Tales();

        $formTale = $this->createForm(TalesFormType::class, $tale);

        $formTale->handleRequest($request);

        if($formTale->isSubmitted() && $formTale->isValid()){
            $drawings = $formTale->get('drawings')->getData();

            foreach($drawings as $drawing){
                $folder = 'tales';
                $file = $pictureService->add($drawing, $folder, 300, 300);

                $image = new Drawings;
                $image->setName($file);
                $tale->addDrawing($image);
            }

            $tale->setUser($this->getUser());
            $slug = $slugger->slug($tale->getTitle());
            $tale->setSlug($slug);

            $manager->persist($tale);
            $manager->flush();

            $this->addFlash('success', 'Le conte a bien été enregistré');

            return $this->redirectToRoute('app_admin_index');
        }

        return $this->render('admin/tales/new.html.twig', [
            'formTale' => $formTale->createView()
        ]);
    }

    #[Route('/{slug}', name:'show', methods: ['GET'])]
    public function show(Tales $tales): Response
    {
        return $this->render('tales/show.html.twig', [
            'tales' => $tales,
        ]);
    }

    #[Route('/admin/edit/{id}', name: 'edit_tale', methods: ['GET', 'POST'])]
    public function editTale(Request $request, Tales $tale, TalesRepository $talesRepository, PictureService $pictureService, SluggerInterface $slugger): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $formTale = $this->createForm(EditTaleFormType::class, $tale);
        $formTale->handleRequest($request);

        if ($formTale->isSubmitted() && $formTale->isValid()){

            $drawings = $formTale->get('drawings')->getData();

            foreach ($drawings as $drawing) {
                $folder = 'tales';
                $file = $pictureService->add($drawing, $folder, 300, 300);

                $image = new Drawings;
                $image->setName($file);
                $tale->addDrawing($image);
            }

            $tale->setUser($this->getUser());
            $slug = $slugger->slug($tale->getTitle());
            $tale->setSlug($slug);
            $tale->setUpdatedAt(new \DateTimeImmutable());
            $talesRepository->save($tale, true);

            $this->addFlash('info', 'Le conte a bien été modifié');

            return $this->redirectToRoute('app_admin_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/tales/edit.html.twig', [
            'tales' => $tale,
            'formTale' => $formTale->createView()
        ]);
    }

    #[Route('/admin/delete/{id}', name: 'delete_tale', methods: ['POST'])]
    public function deleteTale(Tales $tale, Request $request, TalesRepository $talesRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        if($this->isCsrfTokenValid('delete' . $tale->getId(), $request->request->get('_token'))) {
            $talesRepository->remove($tale, true);
        }
        $this->addFlash('success', 'Le conte a bien été supprimé');

        return $this->render('admin/index.html.twig');
    }

    #[Route('/admin/delete/drawing/{id}', name: 'delete_drawing', methods: ['DELETE'])]
    public function deleteDrawing(Drawings $drawing, Request $request, EntityManagerInterface $manager, PictureService $pictureService): JsonResponse
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $data = json_decode($request->getContent(), true);

        if($this->isCsrfTokenValid('delete' . $drawing->getId(), $data['_token'])){
            $drawingName = $drawing->getName();

            if($pictureService->delete($drawingName, 'tales', 300, 300)){
                $manager->remove($drawing);
                $manager->flush();

                return new JsonResponse(['success' => true], 200);
            }
            return new JsonResponse(['error' => 'Erreur de suppression'], 400);
        }
        return new JsonResponse(['error' => ('Token invalide')], 400);
    }
}
