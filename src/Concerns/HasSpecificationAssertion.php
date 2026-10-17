<?php

namespace Rebzya\OpenApiTesting\Concerns;

use Rebzya\OpenApiTesting\Constraints\SpecificationConstraint;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

trait HasSpecificationAssertion
{
    /**
     * Assert that the given value matches the OpenAPI specification for the given path and method.
     */
    public static function assertSpecification(
        string $specification,
        string $path,
        string $method,
        Request|Response $actual,
        string $message = '',
    ): void
    {
        static::assertThat($actual, SpecificationConstraint::make($specification, $path, $method), $message);
    }
}