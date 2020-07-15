<?php

namespace App\Service;

use App\Entity\Note;
use App\Entity\User;
use App\Repository\NotesRepository;
use DateTime;
use DateTimeImmutable;
use RuntimeException;

/**
 * @method NotesRepository getRepository()
 */
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
     * @param User $user
     * @return array|Note[]
     */
    public function listByUser(User $user) : array {
        return $this->getRepository()->findBy([
            'user' => $user,
        ], ['updatedAt' => 'DESC']);
    }

    /**
     * @param User $user
     * @return array|Note[]
     */
    public function listBySharedUser(User $user) : array {
        return $this->getRepository()->findBySharedUser($user);
    }

    /**
     * @param string|null $title
     * @param string|null $body
     * @param User|null $author
     * @return Note
     */
    public function create(?string $title = null, ?string $body = null, ?User $author = null) : Note {
        if (null === $title) {
            throw new RuntimeException('empty title');
        } elseif (!($author instanceof User)) {
            throw new RuntimeException('empty user');
        } else {
            $note = (new Note())
                ->setTitle($title)
                ->setBody($body)
                ->setUser($author)
                ->setCreatedAt(new DateTimeImmutable())
                ->setUpdatedAt(new DateTime())
            ;
            $this->persist($note);

            return $note;
        }
    }

    /**
     * @param int $id
     * @param User $author
     * @return Note|object|null
     */
    public function findOneBy(int $id, User $author) : ?Note {
        return $this->getRepository() ->findOneBy([
            'id' => $id,
            'user' => $author,
        ]);
    }

    /**
     * @param Note $note
     * @param string $title
     * @param string|null $body
     */
    public function update(Note $note, string $title, ?string $body = null) : void {
        $note
            ->setTitle($title)
            ->setBody($body)
            ->setUpdatedAt(new DateTime())
        ;
        $this->persist($note);
    }

    /**
     * @param Note $note
     * @return void
     */
    public function delete(Note $note) : void {
        $this->remove($note);
    }

    public function share(int $id, User $user, ?User $author) : void {

    }

    public function deshare(int $id, User $user, ?User $author) : void {

    }
}