<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

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
    protected $id;
    /**
     * @var string|null
     * @ORM\Column(type="string", length=64, unique=false, nullable=false)
     */
    protected $title;

    /**
     * @var \DateTimeImmutable|null
     * @ORM\Column(type="datetime_immutable")
     */
    protected $createdAt;

    /**
     * @var string|null
     * @ORM\Column(type="text", unique=false, nullable=true)
     */
    protected $body;

    /**
     * @var User|null
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="notes")
     */
    protected $user;

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
