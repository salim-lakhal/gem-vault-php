<?php

namespace App\Tests\Entity;

use App\Entity\Gallery;
use App\Entity\GemCollection;
use App\Entity\Gemstone;
use PHPUnit\Framework\TestCase;

class GemstoneTest extends TestCase
{
    public function testGettersAndSetters(): void
    {
        $gemstone = new Gemstone();
        $date = new \DateTime('2024-06-15');
        $collection = new GemCollection();

        $gemstone->setName('Ruby')
            ->setDescription('Deep red gemstone')
            ->setType('Precious')
            ->setWeight(2.5)
            ->setEstimatedValue(1500.00)
            ->setAcquisitionDate($date)
            ->setColor('Red')
            ->setOrigin('Myanmar')
            ->setRarity('Rare')
            ->setCollection($collection);

        $gemstone->setImageName('ruby.jpg');
        $gemstone->setImageSize(204800);

        self::assertNull($gemstone->getId());
        self::assertSame('Ruby', $gemstone->getName());
        self::assertSame('Deep red gemstone', $gemstone->getDescription());
        self::assertSame('Precious', $gemstone->getType());
        self::assertSame(2.5, $gemstone->getWeight());
        self::assertSame(1500.00, $gemstone->getEstimatedValue());
        self::assertSame($date, $gemstone->getAcquisitionDate());
        self::assertSame('Red', $gemstone->getColor());
        self::assertSame('Myanmar', $gemstone->getOrigin());
        self::assertSame('Rare', $gemstone->getRarity());
        self::assertSame($collection, $gemstone->getCollection());
        self::assertSame('ruby.jpg', $gemstone->getImageName());
        self::assertSame(204800, $gemstone->getImageSize());
    }

    public function testToString(): void
    {
        $gemstone = new Gemstone();
        self::assertSame('', (string) $gemstone);

        $gemstone->setName('Emerald');
        self::assertSame('Emerald', (string) $gemstone);
    }

    public function testGalleryRelationship(): void
    {
        $gemstone = new Gemstone();
        $gallery = new Gallery();

        self::assertCount(0, $gemstone->getGalleries());

        $gemstone->addGallery($gallery);
        self::assertCount(1, $gemstone->getGalleries());
        self::assertTrue($gemstone->getGalleries()->contains($gallery));

        // adding same gallery again should not duplicate
        $gemstone->addGallery($gallery);
        self::assertCount(1, $gemstone->getGalleries());

        $gemstone->removeGallery($gallery);
        self::assertCount(0, $gemstone->getGalleries());
        self::assertFalse($gemstone->getGalleries()->contains($gallery));
    }
}
