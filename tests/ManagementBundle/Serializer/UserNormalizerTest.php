<?php

namespace Tests\ManagementBundle\Serializer;

use ManagementBundle\Entity\User;
use ManagementBundle\Serializer\UserNormalizer;
use PHPUnit\Framework\TestCase;

class UserNormalizerTest extends TestCase
{
    private $userNormalizer;

    public function setUp()
    {
        $this->userNormalizer = new UserNormalizer('rest/v1/users');
    }

    public function testDenormalization()
    {
        $testRequestData = ['name' => 'Test User'];
        $expectedUser = (new User())->setName('Test User');

        $this->assertEquals($expectedUser, $this->userNormalizer->denormalize($testRequestData));
        $this->assertInstanceOf(User::class, $this->userNormalizer->denormalize($testRequestData));
    }

    public function testNormalization()
    {
        $testUser = (new User())->setName('Test User')
            ->setId(1);
        $expectedArray = [
            'id' => 1,
            'name' => 'Test User',
            'href' => 'rest/v1/users/1'
        ];

        $this->assertEquals($expectedArray, $this->userNormalizer->normalize($testUser));
    }
}
