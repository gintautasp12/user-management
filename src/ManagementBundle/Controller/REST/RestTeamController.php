<?php

namespace ManagementBundle\Controller\REST;

use Doctrine\ORM\EntityManager;
use ManagementBundle\Repository\TeamRepository;
use ManagementBundle\Serializer\ArrayNormalizer;
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

    public function __construct(
        EntityManager $entityManager,
        TeamNormalizer $teamNormalizer,
        TeamRepository $teamRepository,
        ArrayNormalizer $arrayNormalizer
    )
    {
        $this->entityManager = $entityManager;
        $this->teamNormalizer = $teamNormalizer;
        $this->teamRepository = $teamRepository;
        $this->arrayNormalizer = $arrayNormalizer;
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
        $team = $this->teamNormalizer->denormalize(json_decode($requestData, true));
        $this->entityManager->persist($team);
        $this->entityManager->flush();

        $this->teamRepository->find($team);

        return new JsonResponse($this->teamNormalizer->normalize($team), Response::HTTP_CREATED);
    }

    /**
     * @return JsonResponse
     */
    public function listAction()
    {
        $teams = $this->arrayNormalizer->mapFromArray(
            $this->teamRepository->findAll(),
            $this->teamNormalizer
        );

        return new JsonResponse($teams, Response::HTTP_OK);
    }
}
