<?php

namespace ManagementBundle\Serializer;

interface NormalizerInterface
{
    public function normalize($object): array;
}
