<?php

namespace App\Repository;

use App\Entity\Note;
use App\Entity\User;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;

class NotesRepository extends EntityRepository
{
    public function findBySharedUser(User $user) {
        $criteria = new Criteria();

        return $this->matching($criteria)->getValues();
    }

    protected function queryBuilder() : QueryBuilder {
        return $this->createQueryBuilder('n')
            ->join('n.shares', 's')
            ->join('s.user', 'u');
    }

    /**
     * @param int $id
     * @param User $user
     * @param string $access
     * @return Note|null
     * @throws NonUniqueResultException
     */
    public function findOneByUserAndAccessAndId(User $user, string $access, int $id) {
        return $this->queryBuilder()
            ->andWhere('s.access = :access')
            ->andWhere('s.user = :user')
            ->andWhere('n.id = :id')
            ->setParameters([
                'access' => $access,
                'user' => $user,
                'id' => $id,
            ])->getQuery()->getOneOrNullResult();
    }

    /**
     * @param User $user
     * @param string $access
     * @return Note[]
     */
    public function findByUserAndAccess(User $user, string $access) {
        return $this->queryBuilder()
            ->andWhere('s.access = :access')
            ->andWhere('s.user = :user')
            ->setParameters([
                'access' => $access,
                'user' => $user,
            ])->getQuery()->getResult();
    }
}
