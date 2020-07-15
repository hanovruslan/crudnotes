<?php

namespace App\Fixtures;

use App\Entity\Note;
use App\Entity\Share;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Exception;

class SharesFixtures extends Fixture implements OrderedFixtureInterface
{
    public function getOrder()
    {
        return 2;
    }

    /**
     * @param ObjectManager $manager
     * @throws Exception
     */
    public function load(ObjectManager $manager)
    {
        /**
         * @var User[] $sharedUsers
         * @var Note $note
         */
        $minSeconds = 12*60*60;
        $maxSeconds = 24*60*60;
        for ($i = 1; $i <= 10; $i++) {
            $sharedNotes = [
                $this->getReference('note_' . $i),
                $this->getReference('note_' . (10 + $i)),
            ];
            $sharedUsers = [
                $this->getReference('user_' . mt_rand(1, 5)),
                $this->getReference('user_' . mt_rand(16, 20)),
            ];
            $accesses = [
                'read',
                'write',
            ];
            foreach ($sharedNotes as $note) {
                foreach ($sharedUsers as $user) {
                    $fixture = (new Share())
                        ->setUser($user)
                        ->setNote($note)
                        ->setAccess($accesses[mt_rand(0, 1)])
                        ->setCreatedAt(new \DateTimeImmutable('- ' . mt_rand($minSeconds, $maxSeconds) . ' seconds'))
                        ->setUpdatedAt(new \DateTime('- ' . mt_rand(0, $minSeconds) . ' seconds'))
                    ;
                    $manager->persist($fixture);
                }
            }
        }

        $manager->flush();
    }
}
