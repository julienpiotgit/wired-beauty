<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\CampaignRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

/**
 * @ApiResource(collectionOperations={
 *          "get"={
 *              "normalization_context"={"groups"={"campaign_read"}}
 *          },
 *          "post"
 *      },
 *     itemOperations={
 *          "get"={
 *              "normalization_context"={"groups"={"campaign_details_read"}}
 *          },
 *          "put",
 *          "patch",
 *          "delete"
 *      })
 * @ApiFilter(SearchFilter::class, properties={"name" :"exact", "date_start" :"exact", "date_end" :"exact"}))
 * @ORM\Entity(repositoryClass=CampaignRepository::class)
 */
class Campaign
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"campaign_read", "campaign_details_read", "user_details_read", "status_read", "status_details_read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"campaign_read", "campaign_details_read", "user_details_read", "status_read", "status_details_read"})
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"campaign_read", "campaign_details_read"})
     */
    private $description;

    /**
     * @ORM\Column(type="date")
     * @Groups({"campaign_read", "campaign_details_read", "user_details_read"})
     */
    private $date_start;

    /**
     * @ORM\Column(type="date")
     * @Groups({"campaign_read", "campaign_details_read", "user_details_read"})
     */
    private $date_end;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"campaign_read", "campaign_details_read"})
     */
    private $file;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $number_tester;

    /**
     * @ORM\ManyToOne(targetEntity=Status::class, inversedBy="campaigns")
     * @Groups({"campaign_read", "campaign_details_read", "user_details_read"})
     */
    private $status;

    /**
     * @ORM\OneToMany(targetEntity=Session::class, mappedBy="campaign", cascade={"persist"})
     * @Groups({"campaign_read", "campaign_details_read"})
     */
    private $sessions;

    /**
     * @ORM\OneToMany(targetEntity=Question::class, mappedBy="campaign")
     * @Groups({"campaign_read", "campaign_details_read"})
     */
    private $questions;

    /**
     * @ORM\OneToMany(targetEntity=Product::class, mappedBy="campaign",cascade={"persist"})
     */
    private $products;

//    /**
//     * @ORM\Column(type="integer")
//     */
//    private $number_tester;

    public function __construct()
    {
        $this->sessions = new ArrayCollection();
        $this->questions = new ArrayCollection();
        $this->products = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getDateStart(): ?\DateTimeInterface
    {
        return $this->date_start;
    }

    public function setDateStart(\DateTimeInterface $date_start): self
    {
        $this->date_start = $date_start;

        return $this;
    }

    public function getDateEnd(): ?\DateTimeInterface
    {
        return $this->date_end;
    }

    public function setDateEnd(\DateTimeInterface $date_end): self
    {
        $this->date_end = $date_end;

        return $this;
    }

    public function getFile(): ?string
    {
        return $this->file;
    }

    public function setFile(string $file): self
    {
        $this->file = $file;

        return $this;
    }

    public function getNumberTester(): ?int
    {
        return $this->number_tester;
    }

    public function setNumberTester(int $number_tester): self
    {
        $this->number_tester = $number_tester;

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

    /**
     * @return Collection<int, Session>
     */
    public function getSessions(): Collection
    {
        return $this->sessions;
    }

    public function addSession(Session $session): self
    {
        if (!$this->sessions->contains($session)) {
            $this->sessions[] = $session;
            $session->setCampaign($this);
        }

        return $this;
    }

    public function removeSession(Session $session): self
    {
        if ($this->sessions->removeElement($session)) {
            // set the owning side to null (unless already changed)
            if ($session->getCampaign() === $this) {
                $session->setCampaign(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Question>
     */
    public function getQuestions(): Collection
    {
        return $this->questions;
    }

    public function addQuestion(Question $question): self
    {
        if (!$this->questions->contains($question)) {
            $this->questions[] = $question;
            $question->setCampaign($this);
        }

        return $this;
    }

    public function removeQuestion(Question $question): self
    {
        if ($this->questions->removeElement($question)) {
            // set the owning side to null (unless already changed)
            if ($question->getCampaign() === $this) {
                $question->setCampaign(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Product>
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
            $product->setCampaign($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->products->removeElement($product)) {
            // set the owning side to null (unless already changed)
            if ($product->getCampaign() === $this) {
                $product->setCampaign(null);
            }
        }

        return $this;
    }

//    public function getNumberTester(): ?int
//    {
//        return $this->number_tester;
//    }
//
//    public function setNumberTester(int $number_tester): self
//    {
//        $this->number_tester = $number_tester;
//
//        return $this;
//    }
}
