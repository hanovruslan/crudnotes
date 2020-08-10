<?php

namespace App\Entity;

use DateTime;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\MaxDepth;

/**
 * @ORM\Entity(repositoryClass="\App\Repository\SharesRepository")
 * @ORM\Table
 */
class Share
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected ?int $id = null;

    /**
     * @var DateTimeImmutable|null
     * @ORM\Column(type="datetime_immutable")
     */
    protected ?DateTimeImmutable $createdAt = null;

    /**
     * @var DateTime|null
     * @ORM\Column(type="datetime")
     */
    protected ?DateTime $updatedAt = null;

    /**
     * @var string|null
     * @ORM\Column(type="string", length=64)
     */
    protected ?string $access = 'read';

    /**
     * @var Note|null
     * @ORM\ManyToOne(targetEntity="App\Entity\Note", inversedBy="shares")
     * @MaxDepth(value=1)
     */
    protected ?Note $note = null;

    /**
     * @var User|null
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="shares")
     * @MaxDepth(value=1)
     */
    protected ?User $user = null;

    /**
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @param User|null $user
     * @return static
     */
    public function setUser(?User $user = null)
    {
        if (($user instanceof User) && !($this->user instanceof User)) {
            $user->addShare($this);
        }
        $this->user = $user;
        
        return $this;
    }
    /**
     * @return Note|null
     */
    public function getNote(): ?Note
    {
        return $this->note;
    }

    /**
     * @param Note|null $note
     * @return static
     */
    public function setNote(?Note $note = null)
    {
        if (($note instanceof Note) && !($this->note instanceof Note)) {
            $note->addShare($this);
        }
        $this->note = $note;

        return $this;
    }

    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     * @return static
     */
    public function setId(?int $id = null)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getAccess(): ?string
    {
        return $this->access;
    }

    /**
     * @param string|null $access
     * @return static
     */
    public function setAccess(?string $access = 'read')
    {
        $this->access = $access;
        return $this;
    }

    /**
     * @return DateTimeImmutable|null
     */
    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * @param DateTimeImmutable|null $createdAt
     * @return static
     */
    public function setCreatedAt(?DateTimeImmutable $createdAt = null)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return DateTime|null
     */
    public function getUpdatedAt(): ?DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @param DateTime|null $updatedAt
     * @return static
     */
    public function setUpdatedAt(?DateTime $updatedAt = null)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}