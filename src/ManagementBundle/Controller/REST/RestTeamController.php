<?php

namespace ManagementBundle\Controller\REST;

use Doctrine\ORM\EntityManager;
use ManagementBundle\Entity\Team;
use ManagementBundle\Entity\User;
use ManagementBundle\Http\RestErrorResponse;
use ManagementBundle\Repository\TeamRepository;
use ManagementBundle\Repository\UserRepository;
use ManagementBundle\Serializer\ArrayNormalizer;
use ManagementBundle\Serializer\Serializer;
use ManagementBundle\Serializer\TeamNormalizer;
use ManagementBundle\Validator\Validator;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolationList;

class RestTeamController
{
    private $entityManager;
    private $teamNormalizer;
    private $teamRepository;
    private $arrayNormalizer;
    private $serializer;
    private $userRepository;
    private $validator;

    public function __construct(
        EntityManager $entityManager,
        TeamNormalizer $teamNormalizer,
        TeamRepository $teamRepository,
        ArrayNormalizer $arrayNormalizer,
        Serializer $serializer,
        UserRepository $userRepository,
        Validator $validator
    )
    {
        $this->entityManager = $entityManager;
        $this->teamNormalizer = $teamNormalizer;
        $this->teamRepository = $teamRepository;
        $this->arrayNormalizer = $arrayNormalizer;
        $this->serializer = $serializer;
        $this->userRepository = $userRepository;
        $this->validator = $validator;
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

        $violations = $this->validator->validate($team);
        if (count($violations) > 0) {
            return new JsonResponse($violations, Response::HTTP_BAD_REQUEST);
        }

        $this->entityManager->persist($team);
        $this->entityManager->flush();

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
        $team = $this->teamRepository->findOneById($id);
        if ($team === null) {
            return new RestErrorResponse('Such team does not exist.', Response::HTTP_NOT_FOUND);
        }

        if (count($team->getUsers()) !== 0) {
            return new RestErrorResponse('This team contains members.', Response::HTTP_BAD_REQUEST);
        }

        $this->entityManager->remove($team);
        $this->entityManager->flush();

        return new JsonResponse([], Response::HTTP_NO_CONTENT);
    }

    /**
     * @param int $id
     * @return RestErrorResponse|JsonResponse
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function individualListAction(int $id)
    {
        $team = $this->teamRepository->findOneById($id);
        if ($team === null) {
            return new RestErrorResponse('Such team does not exist.', Response::HTTP_NOT_FOUND);
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
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function removeFromTeamAction(int $teamId, int $userId)
    {
        /** @var Team $team */
        $team = $this->teamRepository->findOneById($teamId);
        if ($team === null) {
            return new RestErrorResponse('Such team does not exist.', Response::HTTP_NOT_FOUND);
        }
        /** @var User $user */
        $user = $this->userRepository->findOneById($userId);
        if ($user === null) {
            return new RestErrorResponse('Such user does not exist.', Response::HTTP_NOT_FOUND);
        }

        if (!in_array($user, $team->getUsers())) {
            return new RestErrorResponse(
                'This user does not belong to this team.',
                Response::HTTP_BAD_REQUEST
            );
        }

        $team->removeUser($user);
        $this->entityManager->flush();

        return JsonResponse::fromJsonString(
            $this->serializer->serialize($team, $this->teamNormalizer),
            Response::HTTP_OK
        );
    }

    /**
     * @param int $teamId
     * @param int $userId
     * @return JsonResponse
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function addToTeamAction(int $teamId, int $userId)
    {
        /** @var Team $team */
        $team = $this->teamRepository->findOneById($teamId);
        if ($team === null) {
            return new RestErrorResponse('Such team does not exist.', Response::HTTP_NOT_FOUND);
        }

        /** @var User $user */
        $user = $this->userRepository->findOneById($userId);
        if ($user === null) {
            return new RestErrorResponse('Such user does not exist.', Response::HTTP_NOT_FOUND);
        }

        if (in_array($user, $team->getUsers())) {
            return new RestErrorResponse(
                'This user already belongs the given group.',
                Response::HTTP_BAD_REQUEST
            );
        }

        $team->addUser($user);
        $this->entityManager->flush();

        return JsonResponse::fromJsonString(
            $this->serializer->serialize($team, $this->teamNormalizer),
            Response::HTTP_OK
        );
    }
}
