<?php

namespace ManagementBundle\Serializer;

class ArrayNormalizer
{
    public function mapFromArray(array $array, NormalizerInterface $normalizer): array
    {
        $resultArray = [];
        foreach ($array as $object) {
            $resultArray[] = $normalizer->normalize($object);
        }

        return $resultArray;
    }
}
