<?php

namespace App\Service;

use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Persistence\ObjectRepository;

abstract class AbstractService
{
    /**
     * @var ManagerRegistry
     */
    protected $managerRegistry;
    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }

    abstract function getClassName() : string;

    protected function getRepository() : ObjectRepository
    {
        return $this->managerRegistry
            ->getRepository($this->getClassName());
    }

    protected function getManager() : ObjectManager {
        return $this->managerRegistry->getManager();
    }

    /**
     * @param mixed $object
     * @return void
     */
    protected function remove($object) : void {
        $this->getManager()->remove($object);
        $this->getManager()->flush();
    }

    /**
     * @param mixed $object
     */
    public function persist($object) : void {
        $this->getManager()->persist($object);
        $this->getManager()->flush();
    }
}