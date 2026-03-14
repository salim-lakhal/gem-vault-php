<?php

namespace App\Tests\Entity;

use App\Entity\Gallery;
use App\Entity\GemCollection;
use App\Entity\Member;
use PHPUnit\Framework\TestCase;

class MemberTest extends TestCase
{
    public function testGettersAndSetters(): void
    {
        $member = new Member();
        $collection = new GemCollection();

        $member->setEmail('salim@example.com')
            ->setPassword('hashed_password')
            ->setUsername('salim')
            ->setCollection($collection);

        self::assertNull($member->getId());
        self::assertSame('salim@example.com', $member->getEmail());
        self::assertSame('salim@example.com', $member->getUserIdentifier());
        self::assertSame('hashed_password', $member->getPassword());
        self::assertSame('salim', $member->getUsername());
        self::assertSame($collection, $member->getCollection());
    }

    public function testRolesAlwaysContainRoleUser(): void
    {
        $member = new Member();

        // even with empty roles, ROLE_USER is present
        self::assertContains('ROLE_USER', $member->getRoles());

        $member->setRoles(['ROLE_ADMIN']);
        $roles = $member->getRoles();
        self::assertContains('ROLE_USER', $roles);
        self::assertContains('ROLE_ADMIN', $roles);

        // no duplicates when ROLE_USER is explicitly set
        $member->setRoles(['ROLE_USER', 'ROLE_ADMIN']);
        self::assertCount(2, $member->getRoles());
    }

    public function testToString(): void
    {
        $member = new Member();
        self::assertSame('Unknown Member', (string) $member);

        $member->setEmail('collector@gems.io');
        self::assertSame('collector@gems.io', (string) $member);
    }

    public function testGalleryRelationship(): void
    {
        $member = new Member();
        $gallery = new Gallery();

        self::assertCount(0, $member->getGalleries());

        $member->addGallery($gallery);
        self::assertCount(1, $member->getGalleries());
        self::assertSame($member, $gallery->getCreator());

        // adding same gallery again should not duplicate
        $member->addGallery($gallery);
        self::assertCount(1, $member->getGalleries());

        $member->removeGallery($gallery);
        self::assertCount(0, $member->getGalleries());
        self::assertNull($gallery->getCreator());
    }

    public function testEraseCredentials(): void
    {
        $member = new Member();
        $member->setPassword('secret');

        // should not throw
        $member->eraseCredentials();

        // password should still be set (eraseCredentials is a no-op here)
        self::assertSame('secret', $member->getPassword());
    }
}
