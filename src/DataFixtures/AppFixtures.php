<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Dbal\Type\BlogStatus;
use App\Entity\Blog;
use App\Entity\Category;
use App\Entity\Comment;
use App\Entity\Tag;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Random\RandomException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(private readonly UserPasswordHasherInterface $hasher)
    {
    }

    /**
     * @param ObjectManager $manager
     * @return void
     * @throws RandomException
     */
    public function load(ObjectManager $manager): void
    {
        ini_set('max_execution_time', 360);

        $user = new User();
        $user->setEmail('admin@gmail.com');
        $user->setRoles(['ROLE_ADMIN']);
        $password = $this->hasher->hashPassword($user, 'admin@gmail.com');
        $user->setPassword($password);
        $manager->persist($user);

        $users = [];
        for ($i = 0; $i < 100; $i++) {
            $user = new User();
            $user->setEmail('user' . $i . '@gmail.com');
            $user->setRoles(['ROLE_USER']);
            $password = $this->hasher->hashPassword($user, 'pass_1234');
            $user->setPassword($password);
            $manager->persist($user);

            $users[] = $user;
        }

        for ($i = 0; $i < 20; $i++) {
            $tag = (new Tag())->setName(generateRandomString(random_int(3, 15)));
            $manager->persist($tag);
        }

        for ($i = 0; $i < 10; $i++) {
            $category = (new Category())->setName(generateRandomString(random_int(3, 15)));
            $manager->persist($category);
        }

        $blogs = [];
        for ($i = 0; $i < 1000; $i++) {
            shuffle($users);
            foreach ($users as $item) {
                $blog = (new Blog($item))
                    ->setTitle('Blog title ' . $i)
                    ->setPercent(random_int(0, 100))
                    ->setDescription('Blog description ' . $i)
                    ->setStatus(BlogStatus::active)
                    ->setText('Blog text ' . $i);
                $manager->persist($blog);

                $blogs[] = $blog;
            }
        }

        shuffle($blogs);

        /* @var Blog $blog */
        foreach (\array_slice($blogs, 0, 300) as $blog) {
            for ($i = 0; $i < 3; $i++) {
                $comment = (new Comment())
                    ->setText(generateRandomString(random_int(15, 100)))
                    ->setAuthor($blog->getUser())
                    ->setBlog($blog);
                $manager->persist($comment);
            }
        }

        $manager->flush();
    }
}
