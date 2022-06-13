<?php

namespace App\Entity;

use App\Repository\HomePageBuilderRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=HomePageBuilderRepository::class)
 */
class HomePageBuilder
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $principalTitle;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $becomeTesterTitle;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $becomeTesterDescription;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $becomeTesterButton;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrincipalTitle(): ?string
    {
        return $this->principalTitle;
    }

    public function setPrincipalTitle(?string $principalTitle): self
    {
        $this->principalTitle = $principalTitle;

        return $this;
    }

    public function getBecomeTesterTitle(): ?string
    {
        return $this->becomeTesterTitle;
    }

    public function setBecomeTesterTitle(?string $becomeTesterTitle): self
    {
        $this->becomeTesterTitle = $becomeTesterTitle;

        return $this;
    }

    public function getBecomeTesterDescription(): ?string
    {
        return $this->becomeTesterDescription;
    }

    public function setBecomeTesterDescription(?string $becomeTesterDescription): self
    {
        $this->becomeTesterDescription = $becomeTesterDescription;

        return $this;
    }

    public function getBecomeTesterButton(): ?string
    {
        return $this->becomeTesterButton;
    }

    public function setBecomeTesterButton(?string $becomeTesterButton): self
    {
        $this->becomeTesterButton = $becomeTesterButton;

        return $this;
    }
}
