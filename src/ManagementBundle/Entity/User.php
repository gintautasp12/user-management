<?php

namespace ManagementBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;

class User
{
    private $id;
    private $name;
    private $teams;

    public function __construct()
    {
        $this->teams = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getTeams(): ?Team
    {
        return $this->teams->toArray();
    }

    public function addToTeam(Team $team)
    {
        $this->teams->add($team);
    }
}
