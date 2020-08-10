<?php

namespace App\Entity;

use DateTime;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Ignore;

/**
 * @ORM\Entity(repositoryClass="\App\Repository\UsersRepository")
 * @ORM\Table(name="`user`")
 */
class User
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected ?int $id;
    /**
     * @var string|null
     * @ORM\Column(type="string", length=255, unique=true, nullable=false)
     */
    protected ?string $username;

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
     * @ORM\Column(type="string", length=255, unique=false, nullable=true)
     */
    protected ?string $fullname;

    /**
     * @var Note[]|ArrayCollection
     * @ORM\OneToMany(targetEntity="App\Entity\Note", mappedBy="user")
     * @Ignore
     */
    protected $notes;

    /**
     * @var Note[]|ArrayCollection
     * @ORM\OneToMany(targetEntity="App\Entity\Share", mappedBy="user")
     * @Ignore
     */
    protected $shares;

    public function __construct()
    {
        $this->notes = new ArrayCollection();
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
            $share->setUser($this);
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
            $share->setUser(null);
            $this->shares->removeElement($share);
        }

        return $this;
    }

    /**
     * @return Note[]|ArrayCollection
     */
    public function getNotes()
    {
        return $this->notes;
    }

    /**
     * @param Note[]|ArrayCollection $notes
     * @return static
     */
    public function setNotes($notes)
    {
        $this->notes = $notes;
        return $this;
    }

    /**
     * @param Note $note
     * @return static
     */
    public function addNote(Note $note) {
        if (!$this->notes->contains($note)) {
            $this->notes->add($note);
            $note->setUser($this);
        }

        return $this;
    }

    /**
     * @param Note $note
     * @return static
     */
    public function removeNote(Note $note)
    {
        if ($this->notes->contains($note)) {
            $note->setUser(null);
            $this->notes->removeElement($note);
        }

        return $this;
    }

    /**
     * @return string|null
     */
    public function getFullname(): ?string
    {
        return $this->fullname;
    }

    /**
     * @param string|null $fullname
     * @return static
     */
    public function setFullname(?string $fullname = null)
    {
        $this->fullname = $fullname;

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
    public function getUsername(): ?string
    {
        return $this->username;
    }

    /**
     * @param string|null $username
     * @return static
     */
    public function setUsername(?string $username = null)
    {
        $this->username = $username;
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
