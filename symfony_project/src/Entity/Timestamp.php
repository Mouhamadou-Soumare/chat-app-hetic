<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

trait Timestamp
{
    #[ORM\Column(type: 'datetime')]
    private $createdAt;

    public function getCreatedAt():  ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedTime(\DateTimeInterface $date): self
    {
        $this->createdAt = $date;

        return $this;
    }

}