<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setEmail('dhia@symfony.com')
            ->setPassword($this->encoder->encodePassword($user, '123698745'))
            ->setRole('ROLE_ADMIN')
            ->setNom("hachem")
            ->setPrenom("Mohamed dhia")
            ->setUsername("Pintra");

        $manager->persist($user);
        $manager->flush();
    }
}
