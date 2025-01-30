<?php

declare(strict_types=1);

namespace App\Tests\Web\App\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testSomething(): void
    {
        $client = static::createClient();
        $client->request('GET', 'https://127.0.0.1:8000/');

        self::assertResponseIsSuccessful();
        self::assertSelectorTextContains('h1', 'Hello, World');
    }
}
