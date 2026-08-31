<?php

namespace Rebzya\OpenApiTesting\Concerns;

use Rebzya\OpenApiTesting\Constraints\SpecificationConstraint;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

trait HasSpecificationAssertion
{
    /**
     * @param string $specification
     * @param string $path
     * @param string $method
     * @param SymfonyResponse|SymfonyRequest $actual
     * @param string $message
     * @return void
     */
    public static function assertSpecification(
        string $specification,
        string $path,
        string $method,
        mixed $actual,
        string $message = '',
    ): void
    {
        static::assertThat($actual, SpecificationConstraint::make($specification, $path, $method), $message);
    }
}