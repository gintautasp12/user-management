<?php

namespace Tests\ManagementBundle\Serializer;

use ManagementBundle\Entity\Team;
use ManagementBundle\Entity\User;
use ManagementBundle\Serializer\ArrayNormalizer;
use ManagementBundle\Serializer\TeamNormalizer;
use ManagementBundle\Serializer\UserNormalizer;
use PHPUnit\Framework\TestCase;

class ArrayNormalizerTest extends TestCase
{
    public function testMapFromArrayWithTeamNormalizer()
    {
        $teamNormalizerMock = $this->getMockBuilder(TeamNormalizer::class)
            ->disableOriginalConstructor()
            ->setMethods(['normalize'])
            ->getMock();
        $teamNormalizerMock->method('normalize')
            ->will($this->onConsecutiveCalls(
                [
                    'id' => 1,
                    'title' => 'team1',
                ],
                [
                    'id' => 2,
                    'title' => 'team2',
                ],
                [
                    'id' => 3,
                    'title' => 'team3',
                ]
            ));
        $teamArray = [];
        $expectedArray = [];
        $arrayNormalizer = new ArrayNormalizer();
        for ($i = 1; $i <= 3; $i++) {
            $teamArray[] = (new Team())->setId($i)
                ->setTitle("team$i");
            $expectedArray[] = [
                'id' => $i,
                'title' => "team$i"
            ];
        }

        $this->assertEquals($expectedArray, $arrayNormalizer->mapFromArray($teamArray, $teamNormalizerMock));
    }

    public function testMapFromArrayWithUserNormalizer()
    {
        $userNormalizerMock = $this->getMockBuilder(UserNormalizer::class)
            ->disableOriginalConstructor()
            ->setMethods(['normalize'])
            ->getMock();
        $userNormalizerMock->method('normalize')
            ->will($this->onConsecutiveCalls(
                [
                    'id' => 1,
                    'name' => 'user1',
                ],
                [
                    'id' => 2,
                    'name' => 'user2',
                ],
                [
                    'id' => 3,
                    'name' => 'user3',
                ]
            ));
        $teamArray = [];
        $expectedArray = [];
        $arrayNormalizer = new ArrayNormalizer();
        for ($i = 1; $i <= 3; $i++) {
            $teamArray[] = (new User())->setId($i)
                ->setName("user$i");
            $expectedArray[] = [
                'id' => $i,
                'name' => "user$i"
            ];
        }

        $this->assertEquals($expectedArray, $arrayNormalizer->mapFromArray($teamArray, $userNormalizerMock));
    }
}
