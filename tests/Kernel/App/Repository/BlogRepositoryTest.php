<?php

declare(strict_types=1);

namespace App\Tests\Kernel\App\Repository;

use App\Factory\BlogFactory;
use App\Factory\UserFactory;
use App\Repository\BlogRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

class BlogRepositoryTest extends KernelTestCase
{
    use Factories;
    use ResetDatabase;

    public function testSomething(): void
    {
        self::bootKernel();

        $user = UserFactory::createOne();
        BlogFactory::createMany(12, ['user' => $user]);
        $blogRepository = static::getContainer()->get(BlogRepository::class);

        static::assertIsArray($blogRepository->getBlogs());
        static::assertCount(12, $blogRepository->getBlogs());
    }
}
