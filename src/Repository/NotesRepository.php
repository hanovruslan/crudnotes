<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityRepository;

class NotesRepository extends EntityRepository
{
    public function findBySharedUser(User $user) {
        $criteria = new Criteria();

        return $this->matching($criteria)->getValues();
    }
}
