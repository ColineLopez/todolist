<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Task;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class AppFixtures extends Fixture
{

    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {

        $faker = Factory::create();

        $user = new User();
        $user->setUsername('test');
        $user->setEmail('test@example.com');
        $user->setPassword($this->passwordHasher->hashPassword($user, 'password'));
        $manager->persist($user);

        $admin = new User();
        $admin->setUsername('admin');
        $admin->setEmail('admin@example.com');
        $admin->setPassword($this->passwordHasher->hashPassword($admin, 'admin'));
        $admin->setRoles(['ROLE_ADMIN']);
        $manager->persist($admin);

        $anonymous = new User();
        $anonymous->setUsername('anonymous');
        $anonymous->setEmail('anonymous@example.com');
        $anonymous->setPassword($this->passwordHasher->hashPassword($anonymous, 'anonymous'));
        $manager->persist($anonymous);

        $userToEdit = new User();
        $userToEdit->setUsername('user to edit');
        $userToEdit->setEmail('user_to_edit@example.com');
        $userToEdit->setPassword($this->passwordHasher->hashPassword($userToEdit, 'usertoedit'));
        $manager->persist($userToEdit);

        $users = [$user, $admin, $anonymous, $userToEdit];

        for ($i = 0; $i < 20; $i++) {
            $task = new Task();
            $task->setTitle($faker->sentence(6, true));
            $task->setContent($faker->paragraph(3, true));
            $task->setCreatedAt($faker->dateTimeThisYear);
            $task->setUser($faker->randomElement($users));
            $manager->persist($task);
        }

        $manager->flush();
    }
    
}
