<?php

namespace ManagementBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;

class Team
{
    private $id;
    private $title;
    private $users;

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getUsers(): ?array
    {
        return $this->users ? $this->users->toArray() : null;
    }

    public function setUsers(array $users): self
    {
        $this->users->clear();
        /** @var User $user */
        foreach ($users as $user) {
            $this->users->add($user);
            $user->addToTeam($this);
        }

        return $this;
    }

    public function addUser(User $user)
    {
        $this->users->add($user);
        $user->addToTeam($this);
    }

    public function removeUser(User $user)
    {
        $this->users->removeElement($user);
        $user->removeFromTeam($this);
    }

    public function setId($id): self
    {
        $this->id = $id;

        return $this;
    }
}
