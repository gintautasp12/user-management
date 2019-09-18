<?php

namespace ManagementBundle\Validator;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\TraceableValidator;

class Validator extends TraceableValidator
{
    public function validate($value, $constraints = null, $groups = null)
    {
        /** @var ConstraintViolationList $violations */
        $violations = parent::validate($value, $constraints, $groups);
        if (count($violations) > 0) {
            $violationData['errors'] = [];
            foreach ($violations as $violation) {
                $violationData['errors'][] = [
                    'message' => $violation->getMessage(),
                    'property' => $violation->getPropertyPath(),
                    'value' => $violation->getInvalidValue(),
                ];
            }

            return $violationData;
        }

        return [];
    }
}
