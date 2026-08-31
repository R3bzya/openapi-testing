<?php

namespace Rebzya\OpenApiTesting\Concerns;

use Illuminate\Testing\TestResponse;

trait ValidateOpenApiSpecification
{
    use HasSpecificationAssertion;

    protected bool $skipOpenApiSpecificationValidation = false;

    abstract public function openApiSpecification(): string;

    public function call(
        $method,
        $uri,
        $parameters = [],
        $cookies = [],
        $files = [],
        $server = [],
        $content = null,
    ): TestResponse
    {
        $response = parent::call($method, $uri, $parameters, $cookies, $files, $server, $content);

        if (! $this->skipOpenApiSpecificationValidation && $specification = $this->openApiSpecification()) {
            $requestUri = request()->route()?->uri ?: request()->getRequestUri();

            static::assertSpecification($specification, $requestUri, $method, request());
            static::assertSpecification($specification, $requestUri, $method, $response->baseResponse);
        }

        return $response;
    }

    public function skipOpenApiSpecificationValidation(): void
    {
        $this->skipOpenApiSpecificationValidation = true;
    }
}