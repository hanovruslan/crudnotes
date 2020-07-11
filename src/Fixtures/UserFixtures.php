<?php

namespace App\Fixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 20; $i++) {
            $product = new User();
            $product->setUsername('username_' . $i);
            $product->setCreatedAt(new \DateTimeImmutable('- ' . mt_rand(2*24*60*60, 5*24*60*60) . ' seconds'));
            $manager->persist($product);
        }

        $manager->flush();
    }
}
