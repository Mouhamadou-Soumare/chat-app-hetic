<?php

namespace App\Entity;

use App\Repository\MessageRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Index;

#[ORM\Entity(repositoryClass: MessageRepository::class)]
#[Index(name: "created_at_index", columns: ["created_at"])]
#[ORM\HasLifecycleCallbacks]
class Message
{
    use Timestamp;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'text')]
    private $content;
    private $mine;

    #[ORM\ManyToOne(targetEntity: user::class, inversedBy: 'messages')]
    private $user;

    #[ORM\ManyToOne(targetEntity: conversation::class, inversedBy: 'messages')]
    private $conversation;

  

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getConversation(): ?Conversation
    {
        return $this->conversation;
    }

    public function setConversation(?Conversation $conversation): self
    {
       
        $this->conversation = $conversation;

        return $this;
    }

    public function getUser(): ?user
    {
        return $this->user;
    }

    public function setUser(?user $user): self
    {
        $this->user = $user;

        return $this;
    }

    
    public function getMine()
    {
        return $this->mine;
    }

   
    public function setMine($mine): void
    {
        $this->mine = $mine;
    }

}
