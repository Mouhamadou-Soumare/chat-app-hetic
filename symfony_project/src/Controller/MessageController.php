<?php

namespace App\Controller;
use App\Entity\Message;
use App\Entity\Conversation;

use App\Repository\MessageRepository;
use App\Repository\ParticipantRepository;
use App\Repository\UserRepository;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Mercure\Update;

class MessageController extends AbstractController
{

    const ATTRIBUTES_TO_SERIALIZE = ['id', 'content', 'createdAt', 'mine'];

    #[Route('/message/get/{id}', name: 'getMessages',methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function index(Request $request,
     MessageRepository $messageRepository, Conversation $conversation) : Response
    {
     
        $this->denyAccessUnlessGranted('view', $conversation);

        $messages = $messageRepository->findMessageByConversationId(
            $conversation->getId()
        );


        /**
         * @var $message Message
         */
        array_map(function ($message) {
            $message->setMine(
                $message->getUser()->getId() === $this->getUser()->getId()
                    ? true : false
            );
        }, $messages);


        return $this->json($messages, Response::HTTP_OK, [], [
            'attributes' => self::ATTRIBUTES_TO_SERIALIZE
        ]);
    }
   
    #[Route('/message/new/{id}', name: 'newMessages',methods: ['POST'])]
    public function newMessage(Request $request, Conversation $conversation,
    ParticipantRepository $participantRepository,
     SerializerInterface $serializer,EntityManagerInterface $entityManager)
    {
        $user = $this->getUser();

        $recipient = $participantRepository->findParticipantByConverstionIdAndUserId(
            $conversation->getId(),
            $user->getId()
        );
        $content = $request->get('content', "tamerreeer");
        $message = new Message();
        $message->setContent($content);
        $message->setUser($user);
        $message->setCreatedTime(new \DateTime());
        $conversation->addMessage($message);
        $conversation->setLastMessage($message);

        $entityManager->getConnection()->beginTransaction();
        try {
            $entityManager->persist($message);
            $entityManager->persist($conversation);
            $entityManager->flush();
            $entityManager->commit();
        } catch (\Exception $e) {
            $entityManager->rollback();
            throw $e;
        }
        $message->setMine(false);
        $messageSerialized = $serializer->serialize($message, 'json', [
            'attributes' => ['id', 'content', 'createdAt', 'mine', 'conversation' => ['id']]
        ]);
        $update = new Update(
            [
                sprintf("/conversations/%s", $conversation->getId()),
                sprintf("/conversations/%s", $recipient->getUser()->getUsername()),
            ],
            $messageSerialized,
            true
        );

        $this->publisher->__invoke($update);

        $message->setMine(true);
        return $this->json($message, Response::HTTP_CREATED, [], [
            'attributes' => self::ATTRIBUTES_TO_SERIALIZE
        ]);
    }

    
}
