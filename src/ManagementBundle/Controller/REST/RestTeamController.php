<?php

namespace ManagementBundle\Controller\REST;

use Doctrine\ORM\EntityManager;
use ManagementBundle\Repository\TeamRepository;
use ManagementBundle\Serializer\ArrayNormalizer;
use ManagementBundle\Serializer\Serializer;
use ManagementBundle\Serializer\TeamNormalizer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class RestTeamController
{
    private $entityManager;
    private $teamNormalizer;
    private $teamRepository;
    private $arrayNormalizer;
    private $serializer;

    public function __construct(
        EntityManager $entityManager,
        TeamNormalizer $teamNormalizer,
        TeamRepository $teamRepository,
        ArrayNormalizer $arrayNormalizer,
        Serializer $serializer
    )
    {
        $this->entityManager = $entityManager;
        $this->teamNormalizer = $teamNormalizer;
        $this->teamRepository = $teamRepository;
        $this->arrayNormalizer = $arrayNormalizer;
        $this->serializer = $serializer;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function createAction(Request $request)
    {
        $requestData = $request->getContent();
        $team = $this->serializer->deserialize($requestData, $this->teamNormalizer);

        $this->entityManager->persist($team);
        $this->entityManager->flush();
        $this->teamRepository->find($team);

        return JsonResponse::fromJsonString(
            $this->serializer->serialize($team, $this->teamNormalizer),
            Response::HTTP_CREATED
        );
    }

    /**
     * @return JsonResponse
     */
    public function listAction()
    {
        $teams = $this->serializer->serializeCollection(
            $this->teamRepository->findAll(),
            $this->teamNormalizer
        );

        return JsonResponse::fromJsonString($teams, Response::HTTP_OK);
    }
}
