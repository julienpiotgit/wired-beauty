<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\SessionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

/**
 * @ApiResource(collectionOperations={
 *          "get"={
 *              "normalization_context"={"groups"={"session_read"}}
 *          },
 *          "post"
 *      },
 *     itemOperations={
 *          "get"={
 *              "normalization_context"={"groups"={"session_details_read"}}
 *          },
 *          "put",
 *          "patch",
 *          "delete"
 *      })
 * @ApiFilter(SearchFilter::class, properties={"name" :"exact"}))
 * @ORM\Entity(repositoryClass=SessionRepository::class)
 */
class Session
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"session_read", "session_details_read", "user_details_read", "status_read", "status_details_read", "campaign_read", "campaign_details_read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"session_read", "session_details_read", "user_details_read", "status_read", "status_details_read", "campaign_read", "campaign_details_read"})
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity=Campaign::class, inversedBy="sessions")
     * @Groups({"session_read", "session_details_read", "user_details_read"})
     */
    private $campaign;

    /**
     * @ORM\OneToMany(targetEntity=Application::class, mappedBy="session")
     * @Groups({"session_read", "session_details_read"})
     */
    private $applications;

    public function __construct()
    {
        $this->applications = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->getCampaign()->getName();
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

    public function getCampaign(): ?Campaign
    {
        return $this->campaign;
    }

    public function setCampaign(?Campaign $campaign): self
    {
        $this->campaign = $campaign;

        return $this;
    }

    /**
     * @return Collection<int, Application>
     */
    public function getApplications(): Collection
    {
        return $this->applications;
    }

    public function addApplication(Application $application): self
    {
        if (!$this->applications->contains($application)) {
            $this->applications[] = $application;
            $application->setSession($this);
        }

        return $this;
    }

    public function removeApplication(Application $application): self
    {
        if ($this->applications->removeElement($application)) {
            // set the owning side to null (unless already changed)
            if ($application->getSession() === $this) {
                $application->setSession(null);
            }
        }

        return $this;
    }
}
