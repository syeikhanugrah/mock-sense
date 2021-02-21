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
    private int $id;

    /**
     * @ORM\ManyToOne(targetEntity=Api::class, inversedBy="endpoints")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Api $api;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private string $method;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $path;

    /**
     * @ORM\Column(type="integer")
     */
    private int $statusCode;

    /**
     * @ORM\Column(type="text")
     */
    private string $responseBody;

    /**
     * @ORM\OneToMany(targetEntity=EndpointHeader::class, mappedBy="endpoint", orphanRemoval=true)
     */
    private Collection $endpointHeaders;

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

    public function getResponseBody(): ?string
    {
        return $this->responseBody;
    }

    public function setResponseBody(string $responseBody): self
    {
        $this->responseBody = $responseBody;

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
