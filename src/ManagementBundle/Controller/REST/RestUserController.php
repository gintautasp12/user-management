<?php

namespace ManagementBundle\Controller\REST;

use Doctrine\ORM\EntityManager;
use ManagementBundle\Http\RestErrorResponse;
use ManagementBundle\Repository\UserRepository;
use ManagementBundle\Serializer\Serializer;
use ManagementBundle\Serializer\UserNormalizer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class RestUserController
{
    private $serializer;
    private $userNormalizer;
    private $userRepository;
    private $entityManager;

    public function __construct(
        Serializer $serializer,
        UserNormalizer $userNormalizer,
        UserRepository $userRepository,
        EntityManager $entityManager
    )
    {
        $this->serializer = $serializer;
        $this->userNormalizer = $userNormalizer;
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager;
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
        $users = $this->userRepository->findAll();

        return JsonResponse::fromJsonString(
            $this->serializer->serializeCollection($users, $this->userNormalizer),
            Response::HTTP_OK
        );
    }

    /**
     * @return JsonResponse
     * @throws \Doctrine\ORM\ORMException
     */
    public function deleteAction(int $id)
    {
        $user = $this->userRepository->findOneById($id);
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
        $user = $this->userRepository->findOneById($id);
        if ($user === null) {
            return new RestErrorResponse('Such user does not exist.', Response::HTTP_NOT_FOUND);
        }

        return JsonResponse::fromJsonString(
            $this->serializer->serialize($user, $this->userNormalizer),
            Response::HTTP_OK
        );
    }
}
