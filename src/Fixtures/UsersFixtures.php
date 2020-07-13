<?php

namespace App\Fixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class UsersFixtures extends Fixture implements OrderedFixtureInterface
{
    public function getOrder()
    {
        return 0;
    }

    public function load(ObjectManager $manager)
    {
        $names = [
            'Rian Duarte',
            'Ferne Lu',
            'Madelyn Wilkinson',
            'Riya Senior',
            'Ieuan Wu',
            'Tea Rodrigues',
            'Gurpreet John',
            'Maeve Thomson',
            'Samina Mccormack',
            'Kezia Miles',
        ];
        $minSeconds = 2*24*60*60;
        $maxSeconds = 5*24*60*60;
        for ($i = 0; $i < 20; $i++) {
            $user = (new User())
                ->setUsername('username_' . $i)
                ->setFullname($names[mt_rand(0, count($names) - 1)])
                ->setCreatedAt(new \DateTimeImmutable('- ' . mt_rand($minSeconds, $maxSeconds) . ' seconds'));
            $manager->persist($user);
            $this->setReference('user_' . $i, $user);
        }

        $manager->flush();
    }
}
