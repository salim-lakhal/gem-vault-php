<?php

namespace App\DataFixtures;

use App\Entity\Gallery;
use App\Entity\GemCollection;
use App\Entity\Gemstone;
use App\Entity\Member;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(
        private UserPasswordHasherInterface $hasher
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $admin = $this->createMember($manager, 'admin@gemvault.dev', 'admin123', ['ROLE_ADMIN'], 'admin');
        $collector = $this->createMember($manager, 'collector@gemvault.dev', 'collector123', ['ROLE_USER'], 'collector');
        $gemologist = $this->createMember($manager, 'gemologist@gemvault.dev', 'gemologist123', ['ROLE_USER'], 'gemologist');

        $premiumCollection = $this->createCollection($manager, 'Premium Collection', 'Curated selection of the finest gemstones', $admin);
        $vintageStones = $this->createCollection($manager, 'Vintage Stones', 'Classic gemstones from historical mines', $collector);
        $researchSpecimens = $this->createCollection($manager, 'Research Specimens', 'Gemstones for gemological study and analysis', $gemologist);

        $diamond = $this->createGemstone($manager, $premiumCollection, 'Diamond', 'Precious', 1.5, 15000, 'White', 'South Africa', 'Rare', 'A brilliant-cut diamond with exceptional clarity', new \DateTime('2024-01-15'));
        $ruby = $this->createGemstone($manager, $premiumCollection, 'Ruby', 'Precious', 2.1, 8000, 'Red', 'Myanmar', 'Rare', 'Deep pigeon blood ruby with natural fluorescence', new \DateTime('2024-01-20'));
        $sapphire = $this->createGemstone($manager, $premiumCollection, 'Sapphire', 'Precious', 1.8, 6500, 'Blue', 'Sri Lanka', 'Uncommon', 'Ceylon sapphire with vivid cornflower blue', new \DateTime('2024-02-01'));

        $emerald = $this->createGemstone($manager, $vintageStones, 'Emerald', 'Precious', 3.2, 12000, 'Green', 'Colombia', 'Very Rare', 'Colombian emerald with characteristic jardin inclusions', new \DateTime('2024-02-10'));
        $amethyst = $this->createGemstone($manager, $vintageStones, 'Amethyst', 'Semi-precious', 5.0, 200, 'Purple', 'Brazil', 'Common', 'Deep purple amethyst with excellent saturation', new \DateTime('2024-02-15'));
        $topaz = $this->createGemstone($manager, $vintageStones, 'Topaz', 'Semi-precious', 4.5, 350, 'Yellow', 'Brazil', 'Common', 'Imperial topaz with warm golden hue', new \DateTime('2024-03-01'));

        $alexandrite = $this->createGemstone($manager, $researchSpecimens, 'Alexandrite', 'Precious', 0.8, 25000, 'Green/Red', 'Russia', 'Legendary', 'Color-change alexandrite showing green to red shift', new \DateTime('2024-03-10'));
        $tanzanite = $this->createGemstone($manager, $researchSpecimens, 'Tanzanite', 'Semi-precious', 2.5, 3000, 'Violet', 'Tanzania', 'Very Rare', 'Trichroic tanzanite from Merelani Hills', new \DateTime('2024-03-15'));
        $opal = $this->createGemstone($manager, $researchSpecimens, 'Opal', 'Semi-precious', 3.0, 1500, 'Multi', 'Australia', 'Uncommon', 'Black opal with vibrant play-of-color', new \DateTime('2024-03-20'));

        $this->createGallery($manager, 'Precious Gems Showcase', $admin, true, true, [$diamond, $ruby, $sapphire, $emerald]);
        $this->createGallery($manager, "Collector's Private Reserve", $collector, false, true, [$amethyst, $topaz, $emerald]);
        $this->createGallery($manager, 'Rare Specimens', $gemologist, true, true, [$alexandrite, $tanzanite, $opal]);

        $manager->flush();
    }

    private function createMember(ObjectManager $manager, string $email, string $plainPassword, array $roles, string $username): Member
    {
        $member = new Member();
        $member->setEmail($email);
        $member->setRoles($roles);
        $member->setUsername($username);
        $member->setPassword($this->hasher->hashPassword($member, $plainPassword));
        $manager->persist($member);

        return $member;
    }

    private function createCollection(ObjectManager $manager, string $name, string $description, Member $owner): GemCollection
    {
        $collection = new GemCollection();
        $collection->setName($name);
        $collection->setDescription($description);
        $collection->setOwner($owner);
        $collection->setCreatedAt(new \DateTime('2024-01-01'));
        $owner->setCollection($collection);
        $manager->persist($collection);

        return $collection;
    }

    private function createGemstone(
        ObjectManager $manager,
        GemCollection $collection,
        string $name,
        string $type,
        float $weight,
        float $value,
        string $color,
        string $origin,
        string $rarity,
        string $description,
        \DateTime $acquisitionDate
    ): Gemstone {
        $gemstone = new Gemstone();
        $gemstone->setName($name);
        $gemstone->setType($type);
        $gemstone->setWeight($weight);
        $gemstone->setEstimatedValue($value);
        $gemstone->setColor($color);
        $gemstone->setOrigin($origin);
        $gemstone->setRarity($rarity);
        $gemstone->setDescription($description);
        $gemstone->setAcquisitionDate($acquisitionDate);
        $gemstone->setCollection($collection);
        $manager->persist($gemstone);

        return $gemstone;
    }

    /**
     * @param Gemstone[] $gemstones
     */
    private function createGallery(ObjectManager $manager, string $name, Member $creator, bool $isPublic, bool $published, array $gemstones): Gallery
    {
        $gallery = new Gallery();
        $gallery->setName($name);
        $gallery->setCreator($creator);
        $gallery->setIsPublic($isPublic);
        $gallery->setPublished($published);

        foreach ($gemstones as $gemstone) {
            $gallery->addGemstone($gemstone);
        }

        $manager->persist($gallery);

        return $gallery;
    }
}
