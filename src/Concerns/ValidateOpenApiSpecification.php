<?php

namespace Rebzya\OpenApiTesting\Concerns;

use Illuminate\Testing\TestResponse;
use PHPUnit\Framework\Attributes\After;
use PHPUnit\Framework\Attributes\Before;
use RuntimeException;

trait ValidateOpenApiSpecification
{
    use HasSpecificationAssertion;

    protected bool $skipOpenApiSpecificationValidation = false;

    /**
     * @var TestResponse[]
     */
    private array $testResponses = [];

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

        $this->testResponses[] = $response;

        return $response;
    }

    public function skipOpenApiSpecificationValidation(): void
    {
        $this->skipOpenApiSpecificationValidation = true;
    }

    #[Before]
    protected function initializeOpenApiValidation(): void
    {
        $this->testResponses = [];
        $this->skipOpenApiSpecificationValidation = false;
    }

    #[After]
    protected function runOpenApiValidation(): void
    {
        if (! $this->status()->isSuccess() || $this->skipOpenApiSpecificationValidation) {
            return;
        }

        if (! $specification = $this->openApiSpecification()) {
            throw new RuntimeException('Open API specification must be set.');
        }

        if (empty($this->testResponses)) {
            throw new RuntimeException('No API responses were captured for OpenAPI specification validation.');
        }

        foreach ($this->testResponses as $testResponse) {
            $this->runAssertSpecification($specification, $testResponse);
        }
    }

    protected function runAssertSpecification(string $specification, TestResponse $testResponse): void
    {
        $request = $testResponse->baseRequest;
        $response = $testResponse->baseResponse;
        $requestUri = $request->route()?->uri ?: $request->getRequestUri();
        $method = $request->getMethod();

        static::assertSpecification($specification, $requestUri, $method, $request);
        static::assertSpecification($specification, $requestUri, $method, $response);
    }
}