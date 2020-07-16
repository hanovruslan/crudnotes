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

    abstract protected function getClassName() : string;

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
     * @param bool $andFlush
     * @return void
     */
    protected function remove($object, bool $andFlush = true) : void {
        $this->getManager()->remove($object);
        if ($andFlush) {
            $this->getManager()->flush();
        }
    }

    /**
     * @param mixed $object
     * @param bool $andFlush
     */
    public function persist($object, bool $andFlush = true) : void {
        $this->getManager()->persist($object);
        if ($andFlush) {
            $this->getManager()->flush();
        }
    }
}