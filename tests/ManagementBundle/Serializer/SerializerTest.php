<?php

namespace Tests\ManagementBundle\Serializer;

use ManagementBundle\Entity\Team;
use ManagementBundle\Entity\User;
use ManagementBundle\Serializer\ArrayNormalizer;
use ManagementBundle\Serializer\Serializer;
use ManagementBundle\Serializer\TeamNormalizer;
use ManagementBundle\Serializer\UserNormalizer;
use PHPUnit\Framework\TestCase;

class SerializerTest extends TestCase
{
    public function testSerialize()
    {
        $arrayNormalizer = $this->getMockBuilder(ArrayNormalizer::class)
            ->getMock();
        $teamNormalizer = $this->getMockBuilder(TeamNormalizer::class)
            ->disableOriginalConstructor()
            ->setMethods(['normalize'])
            ->getMock();
        $userNormalizer = $this->getMockBuilder(UserNormalizer::class)
            ->disableOriginalConstructor()
            ->setMethods(['normalize'])
            ->getMock();
        $userNormalizer->method('normalize')
            ->willReturn(['id' => 1, 'name' => 'Test User']);
        $teamNormalizer->method('normalize')
            ->willreturn(['id' => 1, 'title' => 'Test Team']);

        $team = (new Team())->setId(1)->setTitle('Test Team');
        $user = (new User())->setId(1)->setName('Test User');
        $serializer = new Serializer($arrayNormalizer);

        $expectedTeamResult = json_encode([
            'data' => ['id' => 1, 'title' => 'Test Team']
        ]);
        $expectedUserResult = json_encode([
            'data' => ['id' => 1, 'name' => 'Test User']
        ]);

        $this->assertEquals($expectedTeamResult, $serializer->serialize($team, $teamNormalizer));
        $this->assertEquals($expectedUserResult, $serializer->serialize($user, $userNormalizer));
    }

    public function testDeserialize()
    {
        $arrayNormalizer = $this->getMockBuilder(ArrayNormalizer::class)
            ->getMock();
        $teamNormalizer = $this->getMockBuilder(TeamNormalizer::class)
            ->disableOriginalConstructor()
            ->setMethods(['denormalize'])
            ->getMock();
        $userNormalizer = $this->getMockBuilder(UserNormalizer::class)
            ->disableOriginalConstructor()
            ->setMethods(['denormalize'])
            ->getMock();
        $userNormalizer->method('denormalize')
            ->willReturn((new User())->setName('Test User'));
        $teamNormalizer->method('denormalize')
            ->willreturn((new Team())->setTitle('Test Team'));

        $serializer = new Serializer($arrayNormalizer);

        $expectedTeamResult = (new Team())->setTitle('Test Team');
        $expectedUserResult = (new User())->setName('Test User');

        $this->assertEquals($expectedTeamResult, $serializer->deserialize('[]', $teamNormalizer));
        $this->assertEquals($expectedUserResult, $serializer->deserialize('[]', $userNormalizer));
    }

    public function testSerializeCollection()
    {
        $arrayNormalizer = $this->getMockBuilder(ArrayNormalizer::class)
            ->getMock();
        $arrayNormalizer->method('mapFromArray')
            ->will($this->onConsecutiveCalls(
                [['id' => 1, 'title' => 'Test Team']],
                [['id' => 1, 'name' => 'Test User']]
            ));
        $teamNormalizer = $this->getMockBuilder(TeamNormalizer::class)
            ->disableOriginalConstructor()
            ->getMock();
        $userNormalizer = $this->getMockBuilder(UserNormalizer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $teamCollection = [(new Team())->setId(1)->setTitle('Test Team')];
        $userCollection = [(new User())->setId(1)->setName('Test User')];
        $serializer = new Serializer($arrayNormalizer);

        $expectedTeamResult = json_encode([
            'data' => [
                ['id' => 1, 'title' => 'Test Team']
            ]
        ]);
        $expectedUserResult = json_encode([
            'data' => [
                ['id' => 1, 'name' => 'Test User']
            ]
        ]);

        $this->assertEquals($expectedTeamResult, $serializer->serializeCollection($teamCollection, $teamNormalizer));
        $this->assertEquals($expectedUserResult, $serializer->serializeCollection($userCollection, $userNormalizer));
    }
}
