<?php
namespace App\DataFixtures;

use App\Entity\Platform;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class PlatformFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $platform1 = new Platform();
        $platform1->setName('OpenAI')
            ->setBaseUrl('https://api.openai.com')
            ->setImageUrl('https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcS3sdA9_gDl2IPpUpEghok54n12APcG_BgBKw&s');

        $manager->persist($platform1);

        $platform2 = new Platform();
        $platform2->setName('DeepSeek')
            ->setBaseUrl('https://api.deepseek.com')
            ->setImageUrl('https://logoeps.com/wp-content/uploads/2025/02/DeepSeek_logo_icon.png');
        $manager->persist($platform2);

        $platform3 = new Platform();
        $platform3->setName('Gemini')
            ->setBaseUrl('https://generativelanguage.googleapis.com')
            ->setImageUrl('https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcThr7qrIazsvZwJuw-uZCtLzIjaAyVW_ZrlEQ&s');

        $manager->persist($platform3);

        $manager->flush();
    }
}
