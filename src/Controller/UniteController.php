<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Unite;


class UniteController extends AbstractController
{
    #[Route('/api/unites', name: 'get_unites')]
    public function getUnites(EntityManagerInterface $entityManager): JsonResponse
    {
        $unites = $entityManager->getRepository(Unite::class)->findAll();

        $unitesArray = [];
        foreach ($unites as $unite) {
            $unitesArray[] = [
                'unitereference' => $unite->getUnitereference(),
                'unitelibelle' => $unite->getUnitelibelle(),
            ];
        }

        return new JsonResponse($unitesArray);
    }
}
