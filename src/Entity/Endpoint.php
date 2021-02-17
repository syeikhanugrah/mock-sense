<?php

namespace App\Entity;

use App\Repository\EndpointRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity(repositoryClass=EndpointRepository::class)
 */
class Endpoint
{
    use TimestampableEntity;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Api::class, inversedBy="endpoints")
     * @ORM\JoinColumn(nullable=false)
     */
    private $api;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $method;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $path;

    /**
     * @ORM\Column(type="integer")
     */
    private $statusCode;

    /**
     * @ORM\OneToMany(targetEntity=EndpointHeader::class, mappedBy="endpoint")
     */
    private $endpointHeaders;

    public function __construct()
    {
        $this->endpointHeaders = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getApi(): ?Api
    {
        return $this->api;
    }

    public function setApi(?Api $api): self
    {
        $this->api = $api;

        return $this;
    }

    public function getMethod(): ?string
    {
        return $this->method;
    }

    public function setMethod(string $method): self
    {
        $this->method = $method;

        return $this;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(string $path): self
    {
        $this->path = $path;

        return $this;
    }

    public function getStatusCode(): ?int
    {
        return $this->statusCode;
    }

    public function setStatusCode(int $statusCode): self
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    public function getEndpointHeaders(): Collection
    {
        return $this->endpointHeaders;
    }

    public function addEndpointHeader(EndpointHeader $endpointHeader): self
    {
        if (!$this->endpointHeaders->contains($endpointHeader)) {
            $this->endpointHeaders[] = $endpointHeader;
            $endpointHeader->setEndpoint($this);
        }

        return $this;
    }

    public function removeEndpointHeader(EndpointHeader $endpointHeader): self
    {
        if ($this->endpointHeaders->removeElement($endpointHeader)) {
            // set the owning side to null (unless already changed)
            if ($endpointHeader->getEndpoint() === $this) {
                $endpointHeader->setEndpoint(null);
            }
        }

        return $this;
    }
}
