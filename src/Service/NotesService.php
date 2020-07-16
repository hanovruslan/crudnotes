<?php

namespace App\Service;

use App\Entity\Note;
use App\Entity\Share;
use App\Entity\User;
use App\Repository\NotesRepository;
use DateTime;
use DateTimeImmutable;
use Doctrine\ORM\NonUniqueResultException;
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
    public function findByUser(User $user) : array {
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
    public function findOneBy(User $author, int $id) : ?Note {
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

    /**
     * @param User $user
     * @param string|null $access
     * @return Note[]
     */
    public function findAvailableBy(User $user, ?string $access = 'read') {
        return $this->getRepository()->findByUserAndAccess($user, $access);
    }

    /**
     * @param User $user
     * @param string $access
     * @param int $id
     * @return Note|null
     * @throws NonUniqueResultException
     */
    public function findOneAvailableBy(User $user, string $access, int $id) {
        return $this->getRepository()->findOneByUserAndAccessAndId($user, $access, $id);
    }
}