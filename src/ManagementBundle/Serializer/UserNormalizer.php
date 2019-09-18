<?php

namespace ManagementBundle\Serializer;

use ManagementBundle\Entity\User;

class UserNormalizer implements NormalizerInterface, DenormalizerInterface
{
    public function denormalize(array $data)
    {
        $user = new User();
        $user->setName($data['name']);

        return $user;
    }

    /**
     * @param User $user
     */
    public function normalize($user): array
    {
        return [
            'id' => $user->getId(),
            'name' => $user->getName(),
            'href' => sprintf("/rest/v1/users/%d", $user->getId())
        ];
    }
}
