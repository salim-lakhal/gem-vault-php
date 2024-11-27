<?php

namespace App\DataFixtures;

use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Member;

class MemberFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager)
    {
        $this->loadMembers($manager);
    }

    private function loadMembers(ObjectManager $manager)
    {
        foreach ($this->getUserData() as [$email,$plainPassword,$role]) {
            $member = new Member();
            $password = $this->hasher->hashPassword($member, $plainPassword);
            $member->setEmail($email);
            $member->setPassword($password);

            $roles = array();
            $roles[] = $role;
            $member->setRoles($roles);

            $manager->persist($member);
        }
        $manager->flush();
    }
    private function getUserData()
    {
        yield [
            'chris@localhost',
            'chris',
            'ROLE_USER'
        ];
        yield [
            'anna@localhost',
            'anna',
            'ROLE_ADMIN'
        ];
    }
}