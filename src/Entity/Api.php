<?php

namespace App\Entity;

use App\Repository\ApiRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity(repositoryClass=ApiRepository::class)
 */
class Api
{
    use TimestampableEntity;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $token;

    /**
     * @ORM\OneToMany(targetEntity=Endpoint::class, mappedBy="api")
     */
    private $endpoints;

    public function __construct()
    {
        $this->endpoints = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

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

    public function getEndpoints(): Collection
    {
        return $this->endpoints;
    }

    public function addEndpoint(Endpoint $endpoint): self
    {
        if (!$this->endpoints->contains($endpoint)) {
            $this->endpoints[] = $endpoint;
            $endpoint->setApi($this);
        }

        return $this;
    }

    public function removeEndpoint(Endpoint $endpoint): self
    {
        if ($this->endpoints->removeElement($endpoint)) {
            // set the owning side to null (unless already changed)
            if ($endpoint->getApi() === $this) {
                $endpoint->setApi(null);
            }
        }

        return $this;
    }
}
