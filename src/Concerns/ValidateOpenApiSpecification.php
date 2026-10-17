<?php

namespace Rebzya\OpenApiTesting\Concerns;

use Illuminate\Testing\TestResponse;
use PHPUnit\Framework\Attributes\After;
use PHPUnit\Framework\Attributes\Before;
use RuntimeException;

trait ValidateOpenApiSpecification
{
    use HasSpecificationAssertion;

    /**
     * Whether OpenAPI specification validation is skipped entirely for the current test.
     */
    protected bool $skipOpenApiSpecificationValidation = false;

    /**
     * Whether the HTTP request should be validated against the OpenAPI specification.
     */
    protected bool $validateOpenApiRequest = true;

    /**
     * Whether the HTTP response should be validated against the OpenAPI specification.
     */
    protected bool $validateOpenApiResponse = true;

    /**
     * All responses captured during the current test, in the order they were made.
     *
     * @var TestResponse[]
     */
    private array $testResponses = [];

    /**
     * Return the path to the OpenAPI specification file used to validate requests and responses.
     */
    abstract public function openApiSpecification(): string;

    /**
     * Perform an HTTP call and capture its response for later OpenAPI specification validation.
     */
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

    /**
     * Skip OpenAPI specification validation entirely for the current test.
     */
    public function skipOpenApiSpecificationValidation(): void
    {
        $this->skipOpenApiSpecificationValidation = true;
    }

    /**
     * Validate the request only, skipping the response validation.
     */
    public function validateOpenApiRequestOnly(): void
    {
        $this->validateOpenApiResponse = false;
    }

    /**
     * Validate the response while allowing the request to be intentionally invalid.
     */
    public function validateOpenApiResponseOnly(): void
    {
        $this->validateOpenApiRequest = false;
    }

    /**
     * Reset the validation state before each test.
     */
    #[Before]
    protected function initializeOpenApiValidation(): void
    {
        $this->testResponses = [];
        $this->skipOpenApiSpecificationValidation = false;
        $this->validateOpenApiRequest = true;
        $this->validateOpenApiResponse = true;
    }

    /**
     * Validate all captured responses against the OpenAPI specification after each test.
     *
     * Skipped when the test failed or when validation was explicitly disabled.
     */
    #[After]
    protected function runOpenApiValidation(): void
    {
        if (! $this->status()->isSuccess() || $this->skipOpenApiSpecificationValidation) {
            return;
        }

        if (! $this->validateOpenApiRequest && ! $this->validateOpenApiResponse) {
            throw new RuntimeException(
                'Both OpenAPI request and response validation are disabled. '
                . 'Use skipOpenApiSpecificationValidation() if you intend to skip validation entirely.',
            );
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

    /**
     * Assert the request and/or response of a single test response against the given specification.
     */
    protected function runAssertSpecification(string $specification, TestResponse $testResponse): void
    {
        $request = $testResponse->baseRequest;
        $response = $testResponse->baseResponse;
        $requestUri = $request->route()?->uri ?: $request->getRequestUri();
        $method = $request->getMethod();

        if ($this->validateOpenApiRequest) {
            static::assertSpecification($specification, $requestUri, $method, $request);
        }

        if ($this->validateOpenApiResponse) {
            static::assertSpecification($specification, $requestUri, $method, $response);
        }
    }
}
