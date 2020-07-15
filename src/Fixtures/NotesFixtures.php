<?php

namespace App\Fixtures;

use App\Entity\Note;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class NotesFixtures extends Fixture implements OrderedFixtureInterface
{
    public function getOrder()
    {
        return 1;
    }

    public function load(ObjectManager $manager)
    {
        /**
         * @var User $user
         */
        $words = explode(' ', 'lorem ipsum dolor sit amet consectetur adipiscing elit sed do eiusmod tempor incididunt ut labore et dolore magna aliqua Fermentum et sollicitudin ac orci phasellus egestas tellus Maecenas accumsan lacus vel facilisis volutpat est velit Amet risus nullam eget felis eget nunc lobortis mattis Sagittis nisl rhoncus mattis rhoncus urna neque viverra justo nec Diam ut venenatis tellus in metus vulputate eu Sed arcu non odio euismod Nisl nisi scelerisque eu ultrices vitae auctor Ac ut consequat semper viverra nam libero Senectus et netus et malesuada fames ac turpis egestas Nascetur ridiculus mus mauris vitae ultricies leo integer malesuada nunc Molestie nunc non blandit massa enim nec Rhoncus aenean vel elit scelerisque mauris pellentesque pulvinar Mattis rhoncus urna neque viverra justo nec ultrices dui sapien Accumsan lacus vel facilisis volutpat est velit egestas dui id Amet consectetur adipiscing elit pellentesque habitant morbi tristique senectus et');
        $minSeconds = 2*24*60*60;
        $maxSeconds = 5*24*60*60;
        for ($i = 1; $i <= 50; $i++) {
            shuffle($words);
            $body = implode(' ', $words);
            $title = substr($body, 0, 60) . ' ...';
            $user = $this->getReference('user_' . mt_rand(1, 20));
            $fixture = (new Note())
                ->setTitle($title)
                ->setBody($body)
                ->setUser($user)
                ->setCreatedAt(new \DateTimeImmutable('- ' . mt_rand($minSeconds, $maxSeconds) . ' seconds'))
                ->setUpdatedAt(new \DateTime('- ' . mt_rand(0, $minSeconds) . ' seconds'))
            ;
            $manager->persist($fixture);
            $this->setReference('note_' . $i, $fixture);
        }

        $manager->flush();
    }
}
