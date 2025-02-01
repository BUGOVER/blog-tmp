<?php

namespace App\Tests\App\Test\ApiTest\App;

use Symfony\Bundle\MakerBundle\Maker\LegacyApiTestCase;

class DefaultControllerTest extends ApiTestCase
{
    public function testSomething(): void
    {
        $response = static::createClient()->request('GET', '/');

        $this->assertResponseIsSuccessful();
        $this->assertJsonContains(['@id' => '/']);
    }
}
