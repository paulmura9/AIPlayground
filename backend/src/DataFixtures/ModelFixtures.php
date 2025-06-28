<?php

namespace App\DataFixtures;

use App\Entity\Model;
use App\Entity\Platform;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ModelFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $platformRepository = $manager->getRepository(Platform::class);

        $openaiPlatform = $platformRepository->findOneBy(['name' => 'OpenAI']);
        $deepseekPlatform = $platformRepository->findOneBy(['name' => 'DeepSeek']);
        $geminiPlatform = $platformRepository->findOneBy(['name' => 'Gemini']);

        $model1 = new Model();
        $model1->setName('gpt-3.5-turbo')
            ->setPlatform($openaiPlatform);

        $manager->persist($model1);

        $model2 = new Model();
        $model2->setName('gpt-4o')
            ->setPlatform($openaiPlatform);

        $manager->persist($model2);

        $model3 = new Model();
        $model3->setName('deepseek-chat')
            ->setPlatform($deepseekPlatform);

        $manager->persist($model3);

        $model4 = new Model();
        $model4->setName('gemini-2.0-flash')
            ->setPlatform($geminiPlatform);

        $manager->persist($model4);

        $model5 = new Model();
        $model5->setName('gemini-2.0-flash-lite')
            ->setPlatform($geminiPlatform);

        $manager->persist($model5);

        $model6 = new Model();
        $model6->setName('deepseek-reasoner')
            ->setPlatform($deepseekPlatform);

        $manager->persist($model6);

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            PlatformFixtures::class,
        ];
    }
}
