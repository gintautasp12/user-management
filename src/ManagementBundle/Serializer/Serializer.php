<?php

namespace ManagementBundle\Serializer;

class Serializer
{
    private $arrayNormalizer;

    public function __construct(ArrayNormalizer $arrayNormalizer)
    {
        $this->arrayNormalizer = $arrayNormalizer;
    }

    public function serialize($entity, NormalizerInterface $normalizer): string
    {
        return json_encode([
            'data' => $normalizer->normalize($entity)
        ]);
    }

    public function serializeCollection(array $collection, NormalizerInterface $normalizer)
    {
        return json_encode([
            'data' => $this->arrayNormalizer->mapFromArray($collection, $normalizer)
        ]);
    }

    public function deserialize(string $jsonString, DenormalizerInterface $denormalizer)
    {
        return $denormalizer->denormalize(json_decode($jsonString, true));
    }
}
