<?php

namespace App\Fixture;

use App\Entity\Note;
use App\Entity\Share;
use App\Entity\User;
use DateTime;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Exception;

class SharesFixture extends Fixture implements OrderedFixtureInterface
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
         * @var Note[] $sharedNotes
         */
        $minSeconds = 12*60*60;
        $maxSeconds = 24*60*60;
        for ($i = 1; $i <= 5; $i++) {
            $sharedNotes = [
                $this->getReference('note_' . $i),
            ];
            $sharedUsers = [
                'read' => $this->getReference('user_' . ($i+1)),
                'write' => $this->getReference('user_' . (10+$i+1)),
            ];
            foreach ($sharedNotes as $note) {
                foreach ($sharedUsers as $access => $user) {
                    $fixture = (new Share())
                        ->setUser($user)
                        ->setNote($note)
                        ->setAccess($access)
                        ->setCreatedAt(new DateTimeImmutable('- ' . mt_rand($minSeconds, $maxSeconds) . ' seconds'))
                        ->setUpdatedAt(new DateTime('- ' . mt_rand(1, $minSeconds) . ' seconds'))
                    ;
                    $manager->persist($fixture);
                }
            }
        }

        $manager->flush();
    }
}
