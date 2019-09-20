<?php

namespace ManagementBundle\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use ManagementBundle\Entity\Team;
use ManagementBundle\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;
    private const USERS = [
        ['name' => 'Mr. Bean'],
        ['name' => 'Lorem ipsum'],
        ['name' => 'Thor'],
        ['name' => 'Susan Doe'],
        ['name' => 'Tom'],
        ['name' => 'Mark Zuckerberg'],
        ['name' => 'Jon'],
        ['name' => 'Peter'],
        ['name' => 'Steve'],
        ['name' => 'Ironman'],
        ['name' => 'Hulk'],
    ];
    private const TEAMS = [
        ['title' => 'Avengers'],
        ['title' => 'Alpha'],
        ['title' => 'Beta'],
        ['title' => 'Gamma'],
        ['title' => 'TeamA'],
        ['title' => 'Office'],
        ['title' => 'Delta'],
    ];

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $this->loadUsers($manager);
        $this->loadTeams($manager);
        $this->loadAdminUser($manager);

        $manager->flush();
    }

    private function loadUsers(ObjectManager $manager)
    {
        foreach (self::USERS as $user) {
            $userFixture = new User();
            $userFixture->setName($user['name']);
            $this->addReference($user['name'], $userFixture);
            $manager->persist($userFixture);
        }
    }

    private function loadTeams(ObjectManager $manager)
    {
        foreach (self::TEAMS as $team) {
            $teamFixture = new Team();
            $teamFixture->setTitle($team['title']);
            for ($i = 0; $i < rand(0, count(self::USERS) - 1); $i++) {
                /** @var User $user */
                $user = $this->getReference(self::USERS[rand(0, count(self::USERS) - 1)]['name']);
                if (!in_array($user, $teamFixture->getUsers())) {
                    $teamFixture->addUser($user);
                }
            }
            $manager->persist($teamFixture);
        }
    }

    private function loadAdminUser(ObjectManager $manager)
    {
        $user = new User();
        $user->setUsername('admin')
            ->setPassword($this->encoder->encodePassword($user, 'password'))
            ->setRoles(['ROLE_ADMIN'])
            ->setName('Admin');
        $manager->persist($user);
    }
}
