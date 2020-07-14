<?php

namespace App\Service;

use App\Entity\Note;
use App\Entity\User;

class NotesService extends AbstractService
{
    function getClassName(): string
    {
        return Note::class;
    }

    /**
     * @return array|Note[]
     */
    public function list() : array {
        return $this->getRepository()->findAll();
    }

    /**
     * @param string|null $title
     * @param string|null $body
     * @param User|null $user
     * @return Note
     */
    public function create(?string $title = null, ?string $body = null, ?User $user = null) : Note {
        if (null === $title) {
            throw new \RuntimeException('empty title');
        } elseif (!($user instanceof User)) {
            throw new \RuntimeException('empty user');
        } else {
            $note = (new Note())
                ->setTitle($title)
                ->setBody($body)
                ->setUser($user)
                ->setCreatedAt(new \DateTimeImmutable());
            $this->persist($note);

            return $note;
        }
    }

    /**
     * @param int $id
     * @return Note|object|null
     */
    public function read(int $id) : ?Note {
        return $this->getRepository() ->find($id);
    }

    /**
     * @param int $id
     * @param string $title
     * @param string|null $body
     * @param User|null $user
     */
    public function update(int $id, string $title, ?string $body = null, ?User $user = null) : void {
        if (!($user instanceof User)) {
            throw new \RuntimeException('empty user');
        }
        $note = $this->getRepository() ->find($id);
        if ($note->getUser()->getUsername() !== $user->getUsername()) {
            throw new \RuntimeException('unable to update note by id: ' . $id);
        }
        if ($note instanceof Note) {
            $note->setTitle($title);
            $note->setBody($body);
            $this->persist($note);
        } else {
            throw new \RuntimeException('note not found by id: ' . $id);
        }
    }

    /**
     * @param int $id
     * @param User|null $user
     * @return void
     */
    public function delete(int $id, ?User $user) : void {
        if (!($user instanceof User)) {
            throw new \RuntimeException('empty user');
        }
        $note = $this->getRepository() ->find($id);
        if ($note instanceof Note) {
            if ($note->getUser()->getUsername() !== $user->getUsername()) {
                throw new \RuntimeException('unable to delete note by id: ' . $id);
            }
            $this->remove($note);
        }
    }
}