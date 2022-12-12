<?php

namespace App\Controller;


use App\Repository\UserRepository;
use App\Repository\ConversationRepository;


use App\Entity\Conversation;
use App\Entity\Participant;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ConversationController extends AbstractController
{

    #[Route('/getconversations', name: 'getConversations', methods: ['GET'])]
    public function getConversations(ConversationRepository $conversationRepository){
        $conversations= $conversationRepository->findConversationsByUser($this->getUser()->getId());
        return $this->json([
            'conversations' => $conversations
        ], 200, [], ['groups' => 'main']); 
    }

    #[Route('/newconversations/{id}', name: 'newConversations', methods: ['POST'])]
    public function index(Request $request,
     UserRepository $userRepository,
     ConversationRepository $conversationRepository,
     EntityManagerInterface $em, int $id): Response
    {
       $otherUser = $request -> get('otherUser',0);

       $otherUser = $userRepository
       ->find($id);
       

       if (is_null($otherUser)){
        throw new \Exception ("the user was not found");
       }

       if ($otherUser->getId() == $this->getUser()->getId()){
        throw new \Exception ("tu t'enviie toi meme ducon");
       }

       $conversation = $conversationRepository
       ->findConversationByParticipants($otherUser->getId(),$this->getUser()->getId());

       if(count($conversation)){
            throw new \Exception("The conversation already exists");
       }

       $conversation = new Conversation();

       $participant = new Participant();
       $participant -> setUser($this->getUser());
       $participant ->setConversation($conversation);

       $otherparticipant = new Participant();
       $otherparticipant -> setUser($otherUser);
       $otherparticipant ->setConversation($conversation);

       $em->getConnection()->beginTransaction();

       try{
            $em->persist($conversation);
            $em->persist($participant);
            $em->persist($otherparticipant);
            $em->flush();
            $em->commit();

       }catch(\Exception $e){
            $em->rollback();
            throw $e;
       }

        return $this->json([
            'id' =>  $conversation -> getId()
        ],Response::HTTP_CREATED, [], [] );
    }

   
}
