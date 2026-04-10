<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UsersFixtures extends Fixture
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
        $faker = Factory::create();
        dump($faker->firstName());

        $admin = new User();
        $admin->setFirstName('root')
        ->setLastName('root')
        ->setEmail('root@root.root')
        ->setRoles(['ROLE_ADMIN']);


        $hashedPassword = $this->passwordHasher->hashPassword($admin, 'root');
        $admin->setPassword($hashedPassword);

        // permet d'ajouter * objets dans la db d'un coup
        $manager->persist($admin);




        for ($i = 0; $i< 10; $i++) {

            $user = new User();

            $hashedPassword = $this->passwordHasher->hashPassword($user, 'user'.$i);

            $user->setFirstName($faker->firstName())
            ->setLastName($faker->lastName())
            ->setEmail($faker->email())
            ->setPassword($hashedPassword);

            // ->setFirstName('FirstName'.$i)
            // ->setLastName('LastName'.$i)
            // ->setEmail('user'.$i.'@email.com');

            $manager->persist($user);
        }

        $manager->flush();
    }
}
