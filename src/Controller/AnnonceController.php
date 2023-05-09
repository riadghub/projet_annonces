<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Entity\Product;
use App\Form\AnnonceFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class AnnonceController extends AbstractController
{
    #[Route('/post', name: 'app_annonce')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {   
        $product = new Product();
        $annonce = new Annonce();
        $form = $this->createForm(AnnonceFormType::class, $annonce);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();
            $imageFile = $form->get('photo')->getData();
            if ($imageFile) {
                // Generate a unique filename
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = $originalFilename.'-'.uniqid().'.'.$imageFile->guessExtension();

                // Move the uploaded file to a directory
                try {
                    $imageFile->move(
                        $this->getParameter('product_images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // Handle the exception
                }

                // Set the imageFilename property on the Product entity
                $product->setPhoto($newFilename);
            }
            $productName = $form->get('product')->getData();
            $productDescription = $form->get('product_description')->getData();
            $productPrice = $form->get('price')->getData();
            $product->setName($productName);
            $product->setDescription($productDescription);
            $product->setPrice($productPrice);
            $product->setCreatedAt(new \DateTimeImmutable);
            
            $entityManager->persist($product);

            $annonce->setCreatedAt(new \DateTimeImmutable);
            $annonce->setAuthor($user);
            $annonce->setUser($user);
            $annonce->setProduct($product);
            $annonce->setCategorie($form->get('categorie')->getData());

            $product->setAnnonce($annonce);
            $entityManager->persist($annonce);
            $entityManager->flush();
        }
        return $this->render('annonce/annonce.html.twig', [
            'annonceForm' => $form->createView(),
        ]);
    }
}
