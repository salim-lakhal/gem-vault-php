<?php

namespace App\Tests\Entity;

use App\Entity\Gallery;
use App\Entity\Gemstone;
use App\Entity\Member;
use PHPUnit\Framework\TestCase;

class GalleryTest extends TestCase
{
    public function testGettersAndSetters(): void
    {
        $gallery = new Gallery();
        $creator = new Member();

        $gallery->setName('Summer Exhibition')
            ->setDescription('Seasonal showcase')
            ->setIsPublic(false)
            ->setPublished(true)
            ->setCreator($creator);

        self::assertNull($gallery->getId());
        self::assertSame('Summer Exhibition', $gallery->getName());
        self::assertSame('Seasonal showcase', $gallery->getDescription());
        self::assertFalse($gallery->isPublic());
        self::assertTrue($gallery->isPublished());
        self::assertSame($creator, $gallery->getCreator());
    }

    public function testGemstoneRelationship(): void
    {
        $gallery = new Gallery();
        $gemstone = new Gemstone();

        self::assertCount(0, $gallery->getGemstones());

        $gallery->addGemstone($gemstone);
        self::assertCount(1, $gallery->getGemstones());
        self::assertTrue($gallery->getGemstones()->contains($gemstone));

        // no duplicate
        $gallery->addGemstone($gemstone);
        self::assertCount(1, $gallery->getGemstones());

        $gallery->removeGemstone($gemstone);
        self::assertCount(0, $gallery->getGemstones());
    }

    public function testIsPublicDefaultsToTrue(): void
    {
        $gallery = new Gallery();
        self::assertTrue($gallery->isPublic());
    }

    public function testToString(): void
    {
        $gallery = new Gallery();
        // no name and no id => "Gallery #"
        self::assertStringStartsWith('Gallery #', (string) $gallery);

        $gallery->setName('Rare Finds');
        self::assertSame('Rare Finds', (string) $gallery);
    }
}
