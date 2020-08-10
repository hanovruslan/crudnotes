<?php

namespace App\Entity;

use DateTime;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Ignore;

/**
 * @ORM\Entity(repositoryClass="App\Repository\NotesRepository")
 */
class Note
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected ?int $id;
    /**
     * @var string|null
     * @ORM\Column(type="string", length=64, unique=false, nullable=false)
     */
    protected ?string $title;

    /**
     * @var DateTimeImmutable|null
     * @ORM\Column(type="datetime_immutable")
     */
    protected ?DateTimeImmutable $createdAt;

    /**
     * @var DateTime|null
     * @ORM\Column(type="datetime")
     */
    protected ?DateTime $updatedAt;

    /**
     * @var string|null
     * @ORM\Column(type="text", unique=false, nullable=true)
     */
    protected ?string $body;

    /**
     * @var User|null
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="notes")
     * @Ignore
     */
    protected ?User $user;

    /**
     * @var Note[]|ArrayCollection
     * @ORM\OneToMany(targetEntity="App\Entity\Share", mappedBy="note")
     * @Ignore
     */
    protected $shares;

    public function __construct()
    {
        $this->shares = new ArrayCollection();
    }

    /**
     * @return Share[]|ArrayCollection
     */
    public function getShares()
    {
        return $this->shares;
    }

    /**
     * @param Share[]|ArrayCollection $shares
     * @return static
     */
    public function setShares($shares)
    {
        foreach ($shares as $share) {
            $this->addShare($share);
        }
        return $this;
    }

    /**
     * @param Share $share
     * @return static
     */
    public function addShare(Share $share) {
        if (!$this->shares->contains($share)) {
            $this->shares->add($share);
            $share->setNote($this);
        }

        return $this;
    }

    /**
     * @param Share $share
     * @return static
     */
    public function removeShare(Share $share)
    {
        if ($this->shares->contains($share)) {
            $share->setNote(null);
            $this->shares->removeElement($share);
        }

        return $this;
    }

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
        $this->user = $user;

        return $this;
    }

    public function getId(): ?int
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
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string|null $title
     * @return static
     */
    public function setTitle(?string $title = null)
    {
        $this->title = $title;
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

    /**
     * @return string|null
     */
    public function getBody(): ?string
    {
        return $this->body;
    }

    /**
     * @param string|null $body
     * @return static
     */
    public function setBody(?string $body = null)
    {
        $this->body = $body;
        return $this;
    }
}
