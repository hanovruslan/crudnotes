<?php

namespace App\Repository;

use App\Entity\Note;
use App\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query\Expr\Composite;

class NotesRepository extends EntityRepository
{
    protected function queryBuilder(bool $withShare = false) : QueryBuilder {
        $qb = $this->createQueryBuilder('n')
            ->leftJoin('n.user', 'nu');
        if ($withShare) {
            $qb->leftJoin('n.shares', 's')
                ->leftJoin('s.user', 'su');
        }

        return $qb;
    }

    public function findOneByIdAndUsername(int $id, string $username) : ?Note {
        return $this->queryBuilderOneByIdAndUsername($id, $username)
            ->getQuery()
            ->getSingleResult();
    }

    public function findByUsername(string $username) : array {
        $qb = $this->queryBuilder();
        $qb->andWhere('nu.username = :username');
        $qb->setParameters([
            'username' => $username,
        ]);

        return $qb->getQuery()
            ->getResult();
    }

    protected function queryBuilderOneByIdAndUsername(int $id, string $username) : QueryBuilder {
        $qb = $this->queryBuilder();
        $qb->setMaxResults(1);
        $qb->where('n.id = :id');
        $qb->andWhere('nu.username = :username');
        $qb->setParameters([
            'id' => $id,
            'username' => $username,
        ]);

        return $qb;
    }

    /**
     * @param int $id
     * @param string $username
     * @param string $access
     * @return Note|null
     * @throws NoResultException|NonUniqueResultException
     */
    public function findOneByIdAndUsernameAndAccess(int $id, string $username, string $access = 'read') : Note {
        $parameters = [
            'id' => $id,
            'username' => $username,
            'read' => 'read',
        ];
        if ('write' === $access) {
            $parameters = array_merge($parameters, [
                $access => $access,
            ]);
        }
        $qb = $this->queryBuilder(true);
        $qb->setMaxResults(1);
        $qb->where('n.id = :id');
        $qb->andWhere($this->buildAccessExpr($qb, $access));
        $qb->setParameters($parameters);

        return $qb->getQuery()->getSingleResult();
    }

    /**
     * @param int $id
     * @param string $username
     * @return bool
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function hasOneByIdAndUsername(int $id, string $username) : bool {
        return 1 === (int) ($this->queryBuilderOneByIdAndUsername($id, $username)
            ->select('count(1)')
            ->getQuery()->getSingleScalarResult());
    }

    /**
     * @param QueryBuilder $qb
     * @param string $access
     * @return Composite
     */
    protected function buildAccessExpr(QueryBuilder $qb, string $access) : Composite {
        $expr = [];
        $expr[] = $qb->expr()->eq('nu.username', ':username');
        $expr[] = $qb->expr()->andX(
            $qb->expr()->eq('su.username', ':username'),
            $qb->expr()->eq('s.access', ':read')
        );
        if ('write' === $access) {
            $expr[] = $qb->expr()->andX(
                $qb->expr()->eq('su.username', ':username'),
                $qb->expr()->eq('s.access', ':write')
            );
        }

        return $qb->expr()->orX(...$expr);
    }

    /**
     * @param User $user
     * @param string $access
     * @return Note[]
     */
    public function findByUserAndAccess(User $user, string $access): array
    {
        return $this->queryBuilder()
            ->andWhere('s.access = :access')
            ->andWhere('s.user = :user')
            ->setParameters([
                'access' => $access,
                'user' => $user,
            ])->getQuery()->getResult();
    }

    /**
     * @param string $username
     * @param string $access
     * @return Note[]|ArrayCollection
     */
    public function findByUsernameAndAccess(string $username, string $access) : array
    {
        $parameters = [
            'username' => $username,
            'access' => $access,
        ];
        $qb = $this->queryBuilder(true);
        $qb->andWhere($qb->expr()->andX(
            $qb->expr()->eq('su.username', ':username'),
            $qb->expr()->eq('s.access', ':access')
        ));
        $qb->setParameters($parameters);

        return $qb->getQuery()->getResult();
    }

    public function findByIdAndUsernamesAndWithoutAccess(int $id, array $usernames, string $access = 'read') : array {

        $parameters = [
            'id' => $id,
            'usernames' => $usernames,
            'access' => $access,
        ];
        $qb = $this->createQueryBuilder('n');
        $qb->join('n.user', 'nu');
        $qb->join('n.shares', 's');
        $qb->join('s.user', 'su');
        $qb->andWhere('n.id = :id');
        $qb->andWhere('s.access = :access');
        $qb->andWhere(
            $qb->expr()->not(
                $qb->expr()->in('su.username', ':usernames'),
            )
        );
        // can you find a bug?
        $qb->setParameters($parameters);

        return $qb->getQuery()->getResult();
    }
}
