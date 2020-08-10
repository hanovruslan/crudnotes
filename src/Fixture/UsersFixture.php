<?php

namespace App\Fixture;

use App\Entity\User;
use DateTime;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Exception;

class UsersFixture extends Fixture implements OrderedFixtureInterface
{
    public function getOrder()
    {
        return 0;
    }

    /**
     * @param ObjectManager $manager
     * @throws Exception
     */
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
        for ($i = 1; $i <= 20; $i++) {
            $fixture = (new User())
                ->setUsername('username_' . $i)
                ->setFullname($names[mt_rand(0, count($names) - 1)])
                ->setCreatedAt(new DateTimeImmutable('- ' . mt_rand($minSeconds, $maxSeconds) . ' seconds'))
                ->setUpdatedAt(new DateTime('- ' . mt_rand(1, $minSeconds) . ' seconds'))
            ;
            $manager->persist($fixture);
            $this->setReference('user_' . $i, $fixture);
        }

        $manager->flush();
    }
}
