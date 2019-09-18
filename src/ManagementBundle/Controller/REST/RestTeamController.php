<?php

namespace ManagementBundle\Controller\REST;

use Doctrine\ORM\EntityManager;
use ManagementBundle\Entity\Team;
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

    /**
     * @return JsonResponse
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function deleteAction(int $id)
    {
        /** @var Team $team */
        $team = $this->teamRepository->findOneBy(['id' => $id]);
        if ($team === null) {
            return new JsonResponse(['error' => [
                'message' => 'Such team does not exist'
            ]], Response::HTTP_NOT_FOUND);
        }

        if (count($team->getUsers()) !== 0) {
            return new JsonResponse(['error' => [
                'message' => 'This team contains members'
            ]], Response::HTTP_METHOD_NOT_ALLOWED);
        }

        $this->entityManager->remove($team);
        $this->entityManager->flush();

        return new JsonResponse([], Response::HTTP_NO_CONTENT);
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function individualListAction(int $id)
    {
        $team = $this->teamRepository->findOneBy(['id' => $id]);
        if ($team === null) {
            return new JsonResponse(['error' => [
                'message' => 'Such team does not exist'
            ]], Response::HTTP_NOT_FOUND);
        }

        return JsonResponse::fromJsonString(
            $this->serializer->serialize($team, $this->teamNormalizer),
            Response::HTTP_OK
        );
    }

    /**
     * @param int $teamId
     * @param int $userId
     * @return JsonResponse
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function removeFromTeamAction(int $teamId, int $userId)
    {
        /** @var Team $team */
        $team = $this->teamRepository->findOneBy(['id' => $teamId]);
        if ($team === null) {
            return new JsonResponse(['error' => [
                'message' => 'Such team does not exist.'
            ]], Response::HTTP_NOT_FOUND);
        }

        $user = $this->teamRepository->findUserById($userId);
        if ($user === null) {
            return new JsonResponse(['error' => [
                'message' => 'Such user does not exist.'
            ]], Response::HTTP_NOT_FOUND);
        }

        $team->removeUser($user);

        return JsonResponse::fromJsonString(
            $this->serializer->serialize($team, $this->teamNormalizer),
            Response::HTTP_OK
        );
    }
}
