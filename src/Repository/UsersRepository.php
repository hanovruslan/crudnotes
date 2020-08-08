<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;

class UsersRepository extends EntityRepository
{
    public function filterByUsernames(array $usernames) : array {
        $sql = 'SELECT username FROM user WHERE username IN (:usernames)';
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult($key = 'username', $key);
        $query = $this->_em->createNativeQuery($sql, $rsm)
            ->setParameter('usernames', $usernames);

        return array_map(static function (array $raw) use ($key) {
            return $raw[$key];
        }, $query->getScalarResult());
    }
}
