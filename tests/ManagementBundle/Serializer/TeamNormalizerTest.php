<?php

namespace Tests\ManagementBundle\Serializer;

use ManagementBundle\Entity\Team;
use ManagementBundle\Serializer\ArrayNormalizer;
use ManagementBundle\Serializer\TeamNormalizer;
use ManagementBundle\Serializer\UserNormalizer;
use PHPUnit\Framework\TestCase;

class TeamNormalizerTest extends TestCase
{
    private $teamNormalizer;

    public function setUp()
    {
        $arrayNormalizerMock = $this->getMockBuilder(ArrayNormalizer::class)
            ->disableOriginalConstructor()
            ->setMethods(['mapFromArray'])
            ->getMock();
        $userNormalizer = $this->getMockBuilder(UserNormalizer::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->teamNormalizer = new TeamNormalizer(
            $arrayNormalizerMock,
            $userNormalizer,
            'rest/v1/teams'
        );
    }

    public function testNormalization()
    {
        $team = new Team();
        $team->setTitle('Test')
            ->setId(1);
        $expectedArray = [
            'id' => 1,
            'title' => 'Test',
            'href' => 'rest/v1/teams/1',
            'users' => []
        ];

        $this->assertEquals($expectedArray, $this->teamNormalizer->normalize($team));
    }

    public function testDenormalization()
    {
        $testRequestData = ['title' => 'New team'];
        $expectedTeamObject = (new Team())->setTitle('New team');

        $this->assertEquals($expectedTeamObject, $this->teamNormalizer->denormalize($testRequestData));
        $this->assertInstanceOf(Team::class, $this->teamNormalizer->denormalize($testRequestData));
    }
}
