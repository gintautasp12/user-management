<?php

namespace ManagementBundle\Controller\REST;

use Doctrine\ORM\EntityManager;
use ManagementBundle\Entity\User;
use ManagementBundle\Http\RestErrorResponse;
use ManagementBundle\Serializer\Serializer;
use ManagementBundle\Serializer\TeamNormalizer;
use ManagementBundle\Serializer\UserNormalizer;
use ManagementBundle\Validator\EntityValidator;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class RestUserController
{
    private $serializer;
    private $userNormalizer;
    private $entityManager;
    private $validator;
    private $teamNormalizer;

    public function __construct(
        Serializer $serializer,
        UserNormalizer $userNormalizer,
        EntityManager $entityManager,
        EntityValidator $validator,
        TeamNormalizer $teamNormalizer
    )
    {
        $this->serializer = $serializer;
        $this->userNormalizer = $userNormalizer;
        $this->entityManager = $entityManager;
        $this->validator = $validator;
        $this->teamNormalizer = $teamNormalizer;
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
        $user = $this->serializer->deserialize($requestData, $this->userNormalizer);

        $violations = $this->validator->validate($user);
        if (count($violations) > 0) {
            return new JsonResponse($violations, Response::HTTP_BAD_REQUEST);
        }

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return JsonResponse::fromJsonString(
            $this->serializer->serialize($user, $this->userNormalizer),
            Response::HTTP_CREATED
        );
    }

    /**
     * @return JsonResponse
     */
    public function listAction()
    {
        $users = $this->entityManager->getRepository('ManagementBundle:User')->findAll();

        return JsonResponse::fromJsonString(
            $this->serializer->serializeCollection($users, $this->userNormalizer),
            Response::HTTP_OK
        );
    }

    /**
     * @param int $id
     * @return RestErrorResponse|JsonResponse
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function listTeamsAction(int $id)
    {
        /** @var User $user */
        $user = $this->entityManager->getRepository('ManagementBundle:User')->findOneById($id);
        if ($user === null) {
            return new RestErrorResponse('Such user does not exist.', Response::HTTP_NOT_FOUND);
        }

        return JsonResponse::fromJsonString(
            $this->serializer->serializeCollection($user->getTeams(), $this->teamNormalizer),
            Response::HTTP_OK
        );
    }

    /**
     * @return JsonResponse
     * @throws \Doctrine\ORM\ORMException
     */
    public function deleteAction(int $id)
    {
        $user = $this->entityManager->getRepository('ManagementBundle:User')->findOneById($id);
        if ($user === null) {
            return new RestErrorResponse('Such user does not exist.', Response::HTTP_NOT_FOUND);
        }

        $this->entityManager->remove($user);
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
        $user = $this->entityManager->getRepository('ManagementBundle:User')->findOneById($id);
        if ($user === null) {
            return new RestErrorResponse('Such user does not exist.', Response::HTTP_NOT_FOUND);
        }

        return JsonResponse::fromJsonString(
            $this->serializer->serialize($user, $this->userNormalizer),
            Response::HTTP_OK
        );
    }
}
