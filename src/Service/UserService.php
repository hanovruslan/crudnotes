<?php

namespace App\Service;

use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;

class UserService
{
    /**
     * @var ManagerRegistry
     */
    protected $managerRegistry;
    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }

    /**
     * @return array|User[]
     */
    public function list() : array {
        return $this->managerRegistry
            ->getRepository(User::class)
            ->findAll();
    }
}