<?php

namespace ManagementBundle\Serializer;

use ManagementBundle\Entity\User;

class UserNormalizer implements NormalizerInterface, DenormalizerInterface
{
    public function denormalize(array $data)
    {
        // TODO: Implement denormalize() method.
    }

    /**
     * @param User $user
     */
    public function normalize($user): array
    {
        return [
            'id' => $user->getId(),
            'name' => $user->getName()
        ];
    }
}
