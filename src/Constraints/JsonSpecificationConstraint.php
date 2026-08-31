<?php

namespace Rebzya\OpenApiTesting\Constraints;

use Rebzya\OpenApiTesting\Validators\CachedValidatorBuilder;
use Osteel\OpenApi\Testing\ValidatorInterface;

class JsonSpecificationConstraint extends SpecificationConstraint
{
    protected function validator(string $definition): ValidatorInterface
    {
        return CachedValidatorBuilder::fromJson($definition);
    }
}