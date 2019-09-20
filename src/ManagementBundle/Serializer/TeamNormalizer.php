<?php

namespace ManagementBundle\Serializer;

use ManagementBundle\Entity\Team;

class TeamNormalizer implements NormalizerInterface, DenormalizerInterface
{
    private $arrayNormalizer;
    private $userNormalizer;
    private $baseHref;

    public function __construct(
        ArrayNormalizer $arrayNormalizer,
        UserNormalizer $userNormalizer,
        string $baseHref
    )
    {
        $this->arrayNormalizer = $arrayNormalizer;
        $this->userNormalizer = $userNormalizer;
        $this->baseHref = $baseHref;
    }

    public function denormalize(array $requestData)
    {
        $team = new Team();
        $team->setTitle($requestData['title']);

        return $team;
    }

    /**
     * @param Team $team
     */
    public function normalize($team): array
    {
        return [
            'id' => $team->getId(),
            'title' => $team->getTitle(),
            'href' => sprintf("%s/%d", $this->baseHref, $team->getId()),
            'users' => $this->arrayNormalizer->mapFromArray($team->getUsers(), $this->userNormalizer)
        ];
    }
}
