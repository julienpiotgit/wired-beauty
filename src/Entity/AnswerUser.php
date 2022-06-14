<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\AnswerUserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

/**
 * @ApiResource(collectionOperations={
 *          "get"={
 *              "normalization_context"={"groups"={"answer_user_read"}}
 *          },
 *          "post"
 *      },
 *     itemOperations={
 *          "get"={
 *              "normalization_context"={"groups"={"answer_user_details_read"}}
 *          },
 *          "put",
 *          "patch",
 *          "delete"
 *      })
 * @ApiFilter(SearchFilter::class, properties={"user" :"exact"}))
 * @ORM\Entity(repositoryClass=AnswerUserRepository::class)
 */
class AnswerUser
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"answer_user_read", "answer_user_details_read"})
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="answerUsers")
     * @Groups({"answer_user_read", "answer_user_details_read"})
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=QuestionAnswer::class, inversedBy="answerUsers")
     * @Groups({"answer_user_read", "answer_user_details_read"})
     */
    private $question_answer;

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

    public function getQuestionAnswer(): ?QuestionAnswer
    {
        return $this->question_answer;
    }

    public function setQuestionAnswer(?QuestionAnswer $question_answer): self
    {
        $this->question_answer = $question_answer;

        return $this;
    }
}
