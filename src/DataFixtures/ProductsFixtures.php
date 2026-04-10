<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Mmo\Faker\PicsumProvider;

class ProductsFixtures extends Fixture implements DependentFixtureInterface
{
    private UserPasswordHasherInterface $passwordHasher;


    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        //
        // dump($faker->firstName());



        // $hashedPassword = $this->passwordHasher->hashPassword($admin, 'root');
        // $admin->setPassword($hashedPassword);

        // permet d'ajouter * objets dans la db d'un coup
        // $manager->persist($admin);



        $faker = Factory::create();
        $faker->addProvider(new PicsumProvider($faker));
        for ($i = 1; $i <= 10; $i++) {

        $category = $this->getReference('Cat'.$faker->numberBetween(1, 10), Category::class);

            $product = new Product()
                ->setName($faker->words(3, true))
                ->setCategory($category)
                ->setPrice($faker->numberBetween(1, 1500))
                ->setSubtitle($faker->words(2, true))
                ->setImage($faker->picsum("C:\laragon\www\maboutique\public\uploads", $width = 640, $height = 480, $fullPath = false, $id = null, $randomize = true, $gray = false, $blur = null, $imageExtension = "jpg"))
                ->setSlug($faker->slug())
                ->setDescription($faker->paragraphs(5, true));

            // $hashedPassword = $this->passwordHasher->hashPassword($user, 'user'.$i);

            $manager->persist($product);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            AppFixtures::class,
        ];
    }
}
