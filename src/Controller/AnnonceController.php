<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Form\AnnonceFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AnnonceController extends AbstractController
{
    #[Route('/annonce', name: 'app_annonce')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $annonce = new Annonce();
        $form = $this->createForm(AnnonceFormType::class, $annonce);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            
            $user = $this->getUser();
            $annonce->setCreatedAt(new \DateTimeImmutable);
            $annonce->setAuthor($user);

            $entityManager->persist($annonce);
            $entityManager->flush();
            // $this->addFlash('success', 'Annonce crée avec succès.');
        }
        return $this->render('annonce/annonce.html.twig', [
            'annonceForm' => $form->createView(),
        ]);
    }
}
