<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;

class SharesRepository extends EntityRepository
{
    public function findByIdAndUsernamesAndAccess(int $id, array $usernames, string $access) {
        $qb = $this->createQueryBuilder('s');
        $qb->join('s.note', 'n');
        $qb->join('s.user', 'u');
        $qb->andWhere('n.id = :id');
        $qb->andWhere('s.access = :access');
        $qb->andWhere('u.username in (:usernames)');
        $qb->setParameters([
            'id' => $id,
            'usernames' => $usernames,
            'access' => $access,
        ]);

        return $qb->getQuery()->getResult();
    }
}
