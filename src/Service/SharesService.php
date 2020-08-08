<?php

namespace App\Service;

use App\Entity\Note;
use App\Entity\Share;
use App\Entity\User;
use App\Repository\NotesRepository;
use App\Repository\SharesRepository;
use App\Repository\UsersRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SharesRepository getRepository()
 */
class SharesService extends AbstractService
{
    /**
     * @var NotesRepository
     */
    protected NotesRepository $notesRepository;

    /**
     * @var UsersRepository
     */
    protected UsersRepository $usersRepository;

    public function __construct(
        ManagerRegistry $managerRegistry,
        NotesRepository $notesRepository,
        UsersRepository $usersRepository
    ) {
        parent::__construct($managerRegistry);
        $this->notesRepository = $notesRepository;
        $this->usersRepository = $usersRepository;
    }



    /**
     * @return array|User[]
     */
    public function list() : array {
        return $this->getRepository()->findAll();
    }

    /**
     * @param int $id
     * @param string[] $usernames
     * @param string $access
     */
    public function share(int $id, array $usernames, string $access = 'read') : void {
        $notes = $this->notesRepository->findByIdAndUsernamesAndWithoutAccess($id, $usernames, $access);
        $users = $this->usersRepository->findBy(['username' => $usernames]);
        die(print_r([
            __FILE__,
            __LINE__,
            array_map(static function (Note $note) {
                return [
                    $note->getId(),
                    $note->getTitle(),
                ];
            }, $notes),
            $usernames,
        ], true));
        foreach ($notes as $note) {
            foreach ($users as $user) {
                $share = (new Share)
                    ->setNote($note)
                    ->setUser($user);
                $this->persist($share, false);
            }
        }

        $this->getManager()->flush();
    }

    public function deshare(int $id, array $usernames, string $access) : void {
        $shares = $this->getRepository()->findByIdAndUsernamesAndAccess($id, $usernames, $access);

        foreach ($shares as $share) {
            $this->remove($share);
        }

        $this->getManager()->flush();
    }

    protected function getClassName(): string
    {
        return Share::class;
    }
}