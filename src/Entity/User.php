<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

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
    protected $id;
    /**
     * @var string|null
     * @ORM\Column(type="string", length=255, unique=true, nullable=false)
     */
    protected $username;

    /**
     * @var \DateTimeImmutable|null
     * @ORM\Column(type="datetime_immutable")
     */
    protected $createdAt;

    /**
     * @var string|null
     * @ORM\Column(type="string", length=255, unique=false, nullable=true)
     */
    protected $fullname;

    /**
     * @var Note[]|array
     * @ORM\OneToMany(targetEntity="App\Entity\Note", mappedBy="user", cascade={"persist"})
     */
    protected $notes = [];

    public function __construct()
    {
        $this->notes = new ArrayCollection();
    }

    /**
     * @return ArrayCollection|Note[]|array
     */
    public function getNotes()
    {
        return $this->notes;
    }

    /**
     * @param Note[]|array $notes
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
     * @return \DateTimeImmutable|null
     */
    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTimeImmutable|null $createdAt
     * @return static
     */
    public function setCreatedAt(?\DateTimeImmutable $createdAt = null)
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
