<?php

namespace App\Service;

use App\Entity\Note;
use App\Entity\User;
use App\Repository\UsersRepository;
use Doctrine\Persistence\ManagerRegistry;

class NotesService extends AbstractService
{
    /**
     * @var UsersRepository|null
     */
    protected $usersRepository;

    public function __construct(ManagerRegistry $managerRegistry, UsersRepository $usersRepository)
    {
        parent::__construct($managerRegistry);
        $this->usersRepository = $usersRepository;
    }

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
     * @param string|null $username
     * @return Note
     */
    public function create(?string $title = null, ?string $body = null, ?string $username = null) : Note {
        if (null === $title) {
            throw new \RuntimeException('empty title');
        } else if (null === $username) {
            throw new \RuntimeException('empty username');
        } elseif (!(($user = $this->usersRepository->findOneBy(['username' => $username])) instanceof User)) {
            throw new \RuntimeException('unknown username: ' . $username);
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

    public function readWithUsername(int $id, string $username) : ?Note {
        /**
         * @var Note $note
         */
        $note = $this->getRepository() ->find($id);
        if ($note->getUser()->getUsername() !== $username) {
            throw new \RuntimeException('unable to read note by id: ' . $id);
        }

        return $note;
    }

    /**
     * @param int $id
     * @param string $title
     * @param string|null $body
     * @param string $username
     */
    public function update(int $id, string $title, ?string $body = null, ?string $username = null) : void {
        $note = $this->readWithUsername($id, $username);
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
     * @return void
     */
    public function deleteWithUsername(int $id, string $username) : void {
        $note = $this->readWithUsername($id, $username);
        if ($note instanceof Note) {
            $this->remove($note);
        }
    }
}