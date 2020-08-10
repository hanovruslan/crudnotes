<?php

namespace App\Service;

use App\Entity\Note;
use App\Entity\User;
use App\Repository\NotesRepository;
use DateTime;
use DateTimeImmutable;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\UnexpectedResultException;
use RuntimeException;

/**
 * @method NotesRepository getRepository()
 */
class NotesService extends AbstractService
{
    /**
     * @return array|Note[]
     */
    public function list() : array {
        return $this->getRepository()->findAll();
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
        }
        if (!($author instanceof User)) {
            throw new RuntimeException('empty user');
        }
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
     * @param string|null $username
     * @return array|Note[]
     */
    public function findByUsername(string $username = null) : array {
        if (null === $username) {
            throw new RuntimeException('empty username');
        }

        return $this->getRepository()->findByUsername($username);
    }

    /**
     * @param int $id
     * @param string|null $username
     * @return bool
     */
    public function hasOneByIdAndUsername(int $id, string $username = null) : bool {
        $result = false;
        if (null === $username) {
            throw new RuntimeException('empty username');
        }
        try {
            $result = $this->getRepository()->hasOneByIdAndUsername($id, $username);
        } catch (UnexpectedResultException $e) {
            // do nothing
        }

        return $result;
    }

    /**
     * @param int $id
     * @param string|null $username
     * @return Note|null
     */
    public function findOneByIdAndUsername(int $id, string $username = null) : ?Note {
        if (null === $username) {
            throw new RuntimeException('empty username');
        }

        return $this->getRepository() ->findOneByIdAndUsername($id, $username);
    }

    /**
     * @param int $id
     * @param string|null $username
     * @param string $access
     * @return Note|null
     * @throws UnexpectedResultException
     */
    public function findOneByIdAndUsernameAndAccess(int $id, string $username = null, string $access = 'read') : ?Note {
        $result = null;
        try {
            if (null === $username) {
                throw new RuntimeException('empty username');
            }
            $result = $this->getRepository() ->findOneByIdAndUsernameAndAccess(
                $id,
                $username,
                $access
            );
        } catch (NoResultException $exception) {
            // do nothing
        }

        return $result;
    }

    /**
     * @param string|null $username
     * @param string|null $access
     * @return Note[]
     */
    public function findAvailableBy(string $username = null, string $access = 'read'): array
    {
        if (null === $username) {
            throw new RuntimeException('empty username');
        }
        return $this->getRepository()->findByUsernameAndAccess($username, $access);
    }

    protected function getClassName(): string
    {
        return Note::class;
    }
}
