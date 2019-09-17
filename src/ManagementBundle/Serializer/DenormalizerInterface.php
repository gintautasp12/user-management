<?php

namespace ManagementBundle\Serializer;

interface DenormalizerInterface
{
    public function denormalize(array $data);
}
