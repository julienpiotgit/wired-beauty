<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\QuestionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

/**
 * @ApiResource(collectionOperations={
 *          "get"={
 *              "normalization_context"={"groups"={"question_read"}}
 *          },
 *          "post"
 *      },
 *     itemOperations={
 *          "get"={
 *              "normalization_context"={"groups"={"question_details_read"}}
 *          },
 *          "put",
 *          "patch",
 *          "delete"
 *      })
 * @ApiFilter(SearchFilter::class, properties={"campaign" :"exact"}))
 * @ORM\Entity(repositoryClass=QuestionRepository::class)
 */
class Question
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"question_read", "question_details_read", "campaign_read", "campaign_details_read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"question_read", "question_details_read", "campaign_read", "campaign_details_read"})
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity=Campaign::class, inversedBy="questions")
     * @Groups({"question_read", "question_details_read"})
     */
    private $campaign;

    /**
     * @ORM\OneToMany(targetEntity=QuestionAnswer::class, mappedBy="question")
     * @Groups({"question_read", "question_details_read"})
     */
    private $questionAnswers;

    public function __construct()
    {
        $this->questionAnswers = new ArrayCollection();
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
     * @return Collection<int, QuestionAnswer>
     */
    public function getQuestionAnswers(): Collection
    {
        return $this->questionAnswers;
    }

    public function addQuestionAnswer(QuestionAnswer $questionAnswer): self
    {
        if (!$this->questionAnswers->contains($questionAnswer)) {
            $this->questionAnswers[] = $questionAnswer;
            $questionAnswer->setQuestion($this);
        }

        return $this;
    }

    public function removeQuestionAnswer(QuestionAnswer $questionAnswer): self
    {
        if ($this->questionAnswers->removeElement($questionAnswer)) {
            // set the owning side to null (unless already changed)
            if ($questionAnswer->getQuestion() === $this) {
                $questionAnswer->setQuestion(null);
            }
        }

        return $this;
    }
}
