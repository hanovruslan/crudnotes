<?php

namespace App\Service;

use App\Entity\Note;
use App\Entity\Share;
use App\Entity\User;
use App\Repository\SharesRepository;
use Doctrine\ORM\QueryBuilder;

/**
 * @method SharesRepository getRepository()
 */
class SharesService extends AbstractService
{
    protected function getClassName(): string
    {
        return Share::class;
    }

    /**
     * @return array|User[]
     */
    public function list() : array {
        return $this->getRepository()->findAll();
    }

    /**
     * @param User $user
     * @param string|null $access
     * @return Share[]
     */
    public function findByUserAndAccess(User $user, ?string $access = 'read') {
        /**
         * @var QueryBuilder $qb
         */
        $qb = $this->getManager()->createQueryBuilder();
        $qb->select('n')
            ->from(Note::class, 'n')
            ->join('n.shares', 's')
            ->join('s.user', 'u')
            ->andWhere('s.access = :access')
            ->andWhere('s.user = :user')
            ->setParameters([
                'access' => $access,
                'user' => $user,
            ])
        ;

        return $qb->getQuery()->getResult();
    }

    /**
     * @param int $id
     * @param User $user
     * @param string|null $access
     * @return Share[]
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findOneByUserAndAccess(int $id, User $user, ?string $access = 'read') {
        /**
         * @var QueryBuilder $qb
         */
        $qb = $this->getManager()->createQueryBuilder();
        $qb->select('n')
            ->from(Note::class, 'n')
            ->join('n.shares', 's')
            ->join('s.user', 'u')
            ->andWhere('s.access = :access')
            ->andWhere('s.user = :user')
            ->andWhere('n.id = :id')
            ->setParameters([
                'access' => $access,
                'user' => $user,
                'id' => $id,
            ])
        ;

        return $qb->getQuery()->getOneOrNullResult();
    }
}