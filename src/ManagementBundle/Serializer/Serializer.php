<?php

namespace ManagementBundle\Serializer;

class Serializer
{
    public function serialize($entity, NormalizerInterface $normalizer)
    {
        return json_encode($normalizer->normalize($entity));
    }

    public function deserialize(string $jsonString, DenormalizerInterface $denormalizer)
    {
        return $denormalizer->denormalize(json_decode($jsonString, true));
    }
}
