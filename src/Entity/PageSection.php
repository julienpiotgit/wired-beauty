<?php

namespace App\Entity;

use App\Repository\PageSectionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PageSectionRepository::class)
 */
class PageSection
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
    private $title;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $button;

    /**
     * @ORM\OneToMany(targetEntity=Point::class, mappedBy="pageSection", cascade={"persist"})
     */
    private $points;

    /**
     * @ORM\ManyToOne(targetEntity=PageBuilder::class, inversedBy="sections")
     */
    private $pageBuilder;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $location;

    public function __construct()
    {
        $this->points = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getButton(): ?string
    {
        return $this->button;
    }

    public function setButton(?string $button): self
    {
        $this->button = $button;

        return $this;
    }

    /**
     * @return Collection<int, Point>
     */
    public function getPoints(): Collection
    {
        return $this->points;
    }

    public function addPoint(Point $point): self
    {
        if (!$this->points->contains($point)) {
            $this->points[] = $point;
            $point->setPageSection($this);
        }

        return $this;
    }

    public function removePoint(Point $point): self
    {
        if ($this->points->removeElement($point)) {
            // set the owning side to null (unless already changed)
            if ($point->getPageSection() === $this) {
                $point->setPageSection(null);
            }
        }

        return $this;
    }

    public function getPageBuilder(): ?PageBuilder
    {
        return $this->pageBuilder;
    }

    public function setPageBuilder(?PageBuilder $pageBuilder): self
    {
        $this->pageBuilder = $pageBuilder;

        return $this;
    }

    public function __toString()
    {
        return $this->getTitle();
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(?string $location): self
    {
        $this->location = $location;

        return $this;
    }
}
