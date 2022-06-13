<?php

namespace App\Entity;

use App\Repository\PageBuilderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PageBuilderRepository::class)
 */
class PageBuilder
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
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $title;

    /**
     * @ORM\OneToMany(targetEntity=PageSection::class, mappedBy="pageBuilder", cascade={"persist"})
     */
    private $sections;

    public function __construct()
    {
        $this->sections = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
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

    /**
     * @return Collection<int, PageSection>
     */
    public function getSections(): Collection
    {
        return $this->sections;
    }

    public function addSection(PageSection $section): self
    {
        if (!$this->sections->contains($section)) {
            $this->sections[] = $section;
            $section->setPageBuilder($this);
        }

        return $this;
    }

    public function removeSection(PageSection $section): self
    {
        if ($this->sections->removeElement($section)) {
            // set the owning side to null (unless already changed)
            if ($section->getPageBuilder() === $this) {
                $section->setPageBuilder(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->getName();
    }
}
