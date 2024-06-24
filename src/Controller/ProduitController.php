<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\ProduitType;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/produit')]
class ProduitController extends AbstractController
{
    private $produitRepository;

    public function __construct(ProduitRepository $produitRepository)
    {
        $this->produitRepository = $produitRepository;
    }

    #[Route('/', name: 'app_produit_index', methods: ['GET'])]
    public function index(ProduitRepository $produitRepository): Response
    {
        return $this->render('produit/index.html.twig', [
            'produits' => $produitRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_produit_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $produit = new Produit();
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($produit);
            $entityManager->flush();

            return $this->redirectToRoute('app_produit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('produit/new.html.twig', [
            'produit' => $produit,
            'form' => $form,
        ]);
    }

    #[Route('/{produitreference}', name: 'app_produit_show', methods: ['GET'])]
    public function show(int $produitreference): Response
    {
        $produit = $this->produitRepository->find($produitreference);

        if (!$produit) {
            throw $this->createNotFoundException('Unable to find Produit with reference number ' . $produitreference);
        }

        return $this->render('produit/show.html.twig', [
            'produit' => $produit,
        ]);
    }

    #[Route('/{produitreference}/edit', name: 'app_produit_edit', methods: ['GET', 'POST'])]
    public function edit(int $produitreference, Request $request, EntityManagerInterface $entityManager): Response
    {
        $produit = $this->produitRepository->find($produitreference);

        if (!$produit) {
            throw $this->createNotFoundException('Unable to find Produit with reference number ' . $produitreference);
        }

        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_produit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('produit/edit.html.twig', [
            'produit' => $produit,
            'form' => $form,
        ]);
    }

    #[Route('/{produitreference}/delete', name: 'app_produit_delete', methods: ['DELETE'])]
    public function delete(int $produitreference, EntityManagerInterface $entityManager): Response
    {
        $produit = $this->produitRepository->find($produitreference);
    
        if (!$produit) {
            throw $this->createNotFoundException('Unable to find Produit with reference number ' . $produitreference);
        }
    
        $entityManager->remove($produit);
        $entityManager->flush();
    
        return $this->redirectToRoute('app_produit_index', [], Response::HTTP_SEE_OTHER);
    }
    
}
