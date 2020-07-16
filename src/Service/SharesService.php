<?php

namespace App\Service;

use App\Entity\Note;
use App\Entity\Share;
use App\Entity\User;
use App\Repository\SharesRepository;

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
     * @param Note $note
     * @param string $access
     * @param User[] $users
     */
    public function share(Note $note, string $access, array $users) : void {
        $shares = $this->getRepository()->findBy([
            'note' => $note,
            'access' => $access,
        ]);
        $userIds = array_map(function (User $user) {
            return $user->getId();
        }, $users);
        $newShares = array_filter($shares, function (Share $share) use ($userIds) {
            return !in_array($share->getUser()->getId(), $userIds);
        });
        foreach ($newShares as $share) {
            foreach ($users as $user) {
                $newShare = clone $share;
                $newShare
                    ->setUser($user)
                    ->setAccess($access)
                ;
                $this->persist($newShare, false);
            }
        }

        $this->getManager()->flush();
    }

    public function deshare(Note $note, string $access, array $users) : void {
        $shares = $this->getRepository()->findBy([
            'note' => $note,
            'access' => $access,
        ]);
        $userIds = array_map(function (User $user) {
            return $user->getId();
        }, $users);
        $removeShares = array_filter($shares, function (Share $share) use ($userIds, $access) {
            return in_array($share->getUser()->getId(), $userIds)
                && $share->getAccess() === $access;
        });
        foreach ($removeShares as $share) {
            $this->remove($share);
        }

        $this->getManager()->flush();
    }
}