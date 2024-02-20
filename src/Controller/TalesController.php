<?php

namespace App\Controller;

use App\Entity\Tales;
use App\Form\TalesFormType;
use App\Repository\TalesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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

    #[Route('/admin/new', name: 'new')]
    public function new(Request $request, EntityManagerInterface $manager, SluggerInterface $slugger): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $tale = new Tales();

        $form = $this->createForm(TalesFormType::class, $tale);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $slug = $slugger->slug($tale->getTitle());
            $tale->setSlug($slug);

            $manager->persist($tale);
            $manager->flush();

            $this->addFlash('success', 'Le conte a bien été enregistré');

            return $this->redirectToRoute('app_admin_index');
        }

        return $this->render('admin/tales/new.html.twig', [
            'form' => $form->createView()
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
