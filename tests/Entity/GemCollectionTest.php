<?php

namespace App\Tests\Entity;

use App\Entity\GemCollection;
use App\Entity\Gemstone;
use App\Entity\Member;
use PHPUnit\Framework\TestCase;

class GemCollectionTest extends TestCase
{
    public function testGettersAndSetters(): void
    {
        $collection = new GemCollection();
        $date = new \DateTime('2024-01-10');
        $owner = new Member();

        $collection->setName('Precious Stones')
            ->setDescription('A curated set of rare gems')
            ->setCreatedAt($date)
            ->setOwner($owner);

        self::assertNull($collection->getId());
        self::assertSame('Precious Stones', $collection->getName());
        self::assertSame('A curated set of rare gems', $collection->getDescription());
        self::assertSame($date, $collection->getCreatedAt());
        self::assertSame($owner, $collection->getOwner());
    }

    public function testGemstoneRelationship(): void
    {
        $collection = new GemCollection();
        $gemstone = new Gemstone();

        self::assertCount(0, $collection->getGemstones());

        $collection->addGemstone($gemstone);
        self::assertCount(1, $collection->getGemstones());
        self::assertSame($collection, $gemstone->getCollection());

        // no duplicate
        $collection->addGemstone($gemstone);
        self::assertCount(1, $collection->getGemstones());

        $collection->removeGemstone($gemstone);
        self::assertCount(0, $collection->getGemstones());
        self::assertNull($gemstone->getCollection());
    }

    public function testToString(): void
    {
        $collection = new GemCollection();
        self::assertSame('', (string) $collection);

        $collection->setName('My Opals');
        self::assertSame('My Opals', (string) $collection);
    }
}
