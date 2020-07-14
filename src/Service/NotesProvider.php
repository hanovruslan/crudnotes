<?php

namespace App\Service;

use Doctrine\Persistence\ManagerRegistry;

class NotesProvider
{
    /**
     * @var UsersService
     */
    protected $usersService;

    /**
     * @var NotesService
     */
    protected $notesService;

    public function __construct(NotesService $notesService, UsersService $usersService)
    {
        $this->notesService = $notesService;
        $this->usersService = $usersService;
    }

    public function getNotesService() : NotesService {
        return $this->notesService;
    }

    public function getUsersService() : UsersService {
        return $this->usersService;
    }
}