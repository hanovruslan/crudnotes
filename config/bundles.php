<?php

return [
    \Symfony\Bundle\FrameworkBundle\FrameworkBundle::class => ['all' => true],
    \Symfony\Bundle\SecurityBundle\SecurityBundle::class => ['all' => true],
    \Doctrine\Bundle\DoctrineBundle\DoctrineBundle::class => ['all' => true],
    \Doctrine\Bundle\MigrationsBundle\DoctrineMigrationsBundle::class => ['dev' => true],
    \Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle::class => ['dev' => true],
    \Symfony\Bundle\WebServerBundle\WebServerBundle::class => ['dev' => true],
    \Symfony\Bundle\DebugBundle\DebugBundle::class => ['dev' => true],
];
