<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ApplicationRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

/**
 * @ApiResource(collectionOperations={
 *          "get"={
 *              "normalization_context"={"groups"={"application_read"}}
 *          },
 *          "post"
 *      },
 *     itemOperations={
 *          "get"={
 *              "normalization_context"={"groups"={"application_details_read"}}
 *          },
 *          "put",
 *          "patch",
 *          "delete"
 *      })
 * @ApiFilter(SearchFilter::class, properties={"user" :"exact", "status" :"exact", "session" :"exact"}))
 * @ORM\Entity(repositoryClass=ApplicationRepository::class)
 */
class Application
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"application_read", "application_details_read", "user_details_read", "status_read", "status_details_read"})
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="applications")
     * @Groups({"application_read", "application_details_read", "status_read", "status_details_read"})
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Session::class, inversedBy="applications")
     * @Groups({"application_read", "application_details_read", "user_details_read", "status_read", "status_details_read"})
     */
    private $session;

    /**
     * @ORM\ManyToOne(targetEntity=Status::class, inversedBy="applications")
     * @Groups({"application_read", "application_details_read", "user_details_read"})
     */
    private $status;

    /**
     * @ORM\Column(type="boolean")
     */
    private $qcm_is_answered;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getSession(): ?Session
    {
        return $this->session;
    }

    public function setSession(?Session $session): self
    {
        $this->session = $session;

        return $this;
    }

    public function getStatus(): ?Status
    {
        return $this->status;
    }

    public function setStatus(?Status $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getQcmIsAnswered(): ?bool
    {
        return $this->qcm_is_answered;
    }

    public function setQcmIsAnswered(bool $qcm_is_answered): self
    {
        $this->qcm_is_answered = $qcm_is_answered;

        return $this;
    }
}
