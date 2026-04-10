<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        //
        for ($i = 1; $i <= 10; $i++) {
            $category = new Category();
            $category->setName('Cat'.$i);
            $manager->persist($category);

            $this->addReference('Cat'.$i, $category);
        }

        $manager->flush();
    }
}
