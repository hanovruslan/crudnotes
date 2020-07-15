<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UsersRepository;

/**
 * @method UsersRepository getRepository()
 */
class UsersService extends AbstractService
{
    function getClassName(): string
    {
        return User::class;
    }

    /**
     * @return array|User[]
     */
    public function list() : array {
        return $this->getRepository()->findAll();
    }

    /**
     * @param string|null $username
     * @param string|null $fullname
     * @return User
     */
    public function create(?string $username = null, ?string $fullname = null) : User {
        if (null === $username) {
            throw new \RuntimeException('empty username');
        } elseif ($this->getRepository()->findBy(['username' => $username])) {
            throw new \RuntimeException('found duplicated username ' . $username);
        } else {
            $user = (new User())
                ->setUsername($username)
                ->setFullname($fullname)
                ->setCreatedAt(new \DateTimeImmutable());
            $this->persist($user);

            return $user;
        }
    }

    /**
     * @param int $id
     * @return User|object|null
     */
    public function read(int $id) : ?User {
        return $this->getRepository() ->find($id);
    }

    /**
     * @param string $username
     * @return User|object|null
     */
    public function findOneByUsername(?string $username = null) : ?User {
        if (null === $username) {
            throw new \RuntimeException('empty username');
        }

        return $this->getRepository() ->findOneBy(['username' => $username]);
    }

    /**
     * @param string[] $usernames
     * @return User[]
     */
    public function findByUsernames(?array $usernames = []) : array {
        return array_map(function (string $username) {
            $user = $this->findOneByUsername($username);
            if (!($user instanceof User)) {
                throw new \RuntimeException('user not found by username' . $username);
            }
        }, $usernames);
    }

    /**
     * @param int $id
     * @param string|null $fullname
     */
    public function update(int $id, ?string $fullname = null) : void {
        $user = $this->read($id);
        if ($user instanceof User) {
            if (null !== $fullname) {
                $user->setFullname($fullname);
                $this->persist($user);
            }
        } else {
            throw new \RuntimeException('user not found by id ' . $id);
        }
    }

    /**
     * @param int $id
     * @return void
     */
    public function delete(int $id) : void {
        $user = $this->read($id);
        if ($user instanceof User) {
            $this->remove($user);
        }
    }
}