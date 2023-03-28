<?php

namespace App\Controller;

use App\Entity\Message;
use App\Form\MessageFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MessageController extends AbstractController
{
    #[Route('/message', name: 'app_message')]
    public function index(): Response
    {
        return $this->render('message/index.html.twig', [
            'controller_name' => 'MessageController',
        ]);
    }
    #[Route('/send', name: 'app_send')]
    public function send(Request $request, EntityManagerInterface $entityManager): Response{
        $message = new Message;
        $form = $this->createForm(MessageFormType::class, $message);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $message->setSender($this->getUser());
            $message->setCreatedAt(new \DateTimeImmutable());
            $message->setIsRead(false);
            $entityManager->persist($message);
            $entityManager->flush();

            $this->addFlash('message','Message envoyé avec succès.');
            return $this->redirectToRoute('app_message');
        }
        return $this->render('message/send.html.twig', [
            'form' => $form->createView()
        ]);
    }
    #[Route('/received', name: 'app_received')]
    public function received(): Response
    {
        return $this->render('message/received.html.twig', [
            'controller_name' => 'MessageController',
        ]);
    }
    #[Route('/read', name: 'app_read')]
    public function read(Message $message, EntityManagerInterface $entityManager): Response
    {
        $message->setIsRead(true);

        $entityManager->persist($message);
        $entityManager->flush();

        return $this->render('message/read.html.twig', [
            'controller_name' => 'MessageController',
            compact('message'),
        ]);
    }
}
