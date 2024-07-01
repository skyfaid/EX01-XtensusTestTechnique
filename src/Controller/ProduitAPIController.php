<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Entity\Unite;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/produits')]
class ProduitAPIController extends AbstractController
{
    private $entityManager;
    private $produitRepository;

    public function __construct(EntityManagerInterface $entityManager, ProduitRepository $produitRepository)
    {
        $this->entityManager = $entityManager;
        $this->produitRepository = $produitRepository;
    }

   /* #[Route('/', name: 'produit_index', methods: ['GET'])]
    public function index(): Response
    {
        $produits = $this->produitRepository->findAll();
        return $this->json($produits, Response::HTTP_OK, [], ['groups' => 'produit:read']);
    }*/
    #[Route('/', name: 'produit_index', methods: ['GET'])]
    public function index(): Response
    {
        $produits = $this->produitRepository->findAllWithUnite(); // Assuming you have a custom method to fetch produits with unite details
        
        // Transform produits to include unitelibelle instead of unitereference
        $data = [];
        foreach ($produits as $produit) {
            $data[] = [
                'produitreference' => $produit->getProduitreference(),
                'produitlibelle' => $produit->getProduitlibelle(),
                'produitdescription' => $produit->getProduitdescription(),
                'unitelibelle' => $produit->getUnitereference()->getUnitelibelle(),
            ];
        }

        return $this->json($data, Response::HTTP_OK);
    }

    #[Route('/{id}', name: 'produit_show', methods: ['GET'])]
    public function show(Produit $produit): Response
    {
        // Fetch unite libelle for the produit
        $unitelibelle = $produit->getUnitereference()->getUnitelibelle();

        // Prepare data including unite libelle
        $data = [
            'produitreference' => $produit->getProduitreference(),
            'produitlibelle' => $produit->getProduitlibelle(),
            'produitdescription' => $produit->getProduitdescription(),
            'unitelibelle' => $unitelibelle,
            'unitereference'=>$produit->getUnitereference()->getUnitereference()
        ];

        return $this->json($data, Response::HTTP_OK);
    }

    #[Route('/new', name: 'produit_new', methods: ['POST'])]
    public function new(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        $produit = new Produit();
        $produit->setProduitlibelle($data['produitlibelle']);
        $produit->setProduitdescription($data['produitdescription']);
        // Assuming unitereference is an ID and you fetch the actual Unite entity
        $unitereference = $this->entityManager->getRepository(Unite::class)->find($data['unitereference']);
        $produit->setUnitereference($unitereference);

        $this->entityManager->persist($produit);
        $this->entityManager->flush();

        return $this->json($produit, Response::HTTP_CREATED, [], ['groups' => 'produit:read']);
    }

    #[Route('/{id}/edit', name: 'produit_edit', methods: ['PUT'])]
    public function edit(Request $request, Produit $produit): Response
    {
        $data = json_decode($request->getContent(), true);
        $produit->setProduitlibelle($data['produitlibelle']);
        $produit->setProduitdescription($data['produitdescription']);
        $unitereference = $this->entityManager->getRepository(Unite::class)->find($data['unitereference']);
        $produit->setUnitereference($unitereference);

        $this->entityManager->flush();

        return $this->json($produit, Response::HTTP_OK, [], ['groups' => 'produit:read']);
    }

    #[Route('/{id}/delete', name: 'produit_delete', methods: ['DELETE'])]
    public function delete(Produit $produit): Response
    {
        $this->entityManager->remove($produit);
        $this->entityManager->flush();

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }
}
