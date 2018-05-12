<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DownloadTicketRepository")
 */
class DownloadTicket
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=40)
     */
    private $idUser;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $token;

    /**
     * @ORM\Column(type="boolean")
     */
    private $used;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $temporaryLocation;

    public function getId()
    {
        return $this->id;
    }

    public function getIdUser(): ?string
    {
        return $this->idUser;
    }

    public function setIdUser(string $idUser): self
    {
        $this->idUser = $idUser;

        return $this;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(string $token): self
    {
        $this->token = $token;

        return $this;
    }

    public function getUsed(): ?bool
    {
        return $this->used;
    }

    public function setUsed(bool $used): self
    {
        $this->used = $used;

        return $this;
    }

    public function getTemporaryLocation(): ?string
    {
        return $this->temporaryLocation;
    }

    public function setTemporaryLocation(string $temporaryLocation): self
    {
        $this->temporaryLocation = $temporaryLocation;

        return $this;
    }
}
