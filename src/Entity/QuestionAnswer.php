<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\QuestionAnswerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

/**
 * @ApiResource(collectionOperations={
 *          "get"={
 *              "normalization_context"={"groups"={"question_answer_read"}}
 *          },
 *          "post"
 *      },
 *     itemOperations={
 *          "get"={
 *              "normalization_context"={"groups"={"question_answer_details_read"}}
 *          },
 *          "put",
 *          "patch",
 *          "delete"
 *      })
 * @ApiFilter(SearchFilter::class, properties={"question" :"exact"}))
 * @ORM\Entity(repositoryClass=QuestionAnswerRepository::class)
 */
class QuestionAnswer
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"question_answer_read", "question_answer_details_read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"question_answer_read", "question_answer_details_read"})
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity=Question::class, inversedBy="questionAnswers")
     * @Groups({"question_answer_read", "question_answer_details_read"})
     */
    private $question;

    /**
     * @ORM\OneToMany(targetEntity=AnswerUser::class, mappedBy="question_answer")
     * @Groups({"question_answer_read", "question_answer_details_read"})
     */
    private $answerUsers;

    public function __construct()
    {
        $this->answerUsers = new ArrayCollection();
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

    public function getQuestion(): ?Question
    {
        return $this->question;
    }

    public function setQuestion(?Question $question): self
    {
        $this->question = $question;

        return $this;
    }

    /**
     * @return Collection<int, AnswerUser>
     */
    public function getAnswerUsers(): Collection
    {
        return $this->answerUsers;
    }

    public function addAnswerUser(AnswerUser $answerUser): self
    {
        if (!$this->answerUsers->contains($answerUser)) {
            $this->answerUsers[] = $answerUser;
            $answerUser->setQuestionAnswer($this);
        }

        return $this;
    }

    public function removeAnswerUser(AnswerUser $answerUser): self
    {
        if ($this->answerUsers->removeElement($answerUser)) {
            // set the owning side to null (unless already changed)
            if ($answerUser->getQuestionAnswer() === $this) {
                $answerUser->setQuestionAnswer(null);
            }
        }

        return $this;
    }
}
