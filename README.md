# OpenAPI Testing

This package allows you to integrate OpenAPI specification validation into your test flow.

## Installation

> **Note**  
> This package is primarily intended to be used as part of an API test suite.

Via Composer:

```shell
composer require --dev rebzya/openapi-testing
```

## Usage

You can use either `\Rebzya\OpenApiTesting\Concerns\HasSpecificationAssertion` or `\Rebzya\OpenApiTesting\Concerns\ValidateOpenApiSpecification`.
Add one of them to your `TestCase` class.

### HasSpecificationAssertion

The `HasSpecificationAssertion` trait adds the `assertSpecification` method
to your `TestCase` class. This may be useful if you need to check one specific case.

### ValidateOpenApiSpecification

The `ValidateOpenApiSpecification` trait integrates into the `call` method,
allowing your application to validate the specification during the test flow.

> **Note**  
> If, for some reason, you do not need to validate the specification, disable it in your test method with the `skipOpenApiSpecificationValidation()` method,
> or disable it globally for the test class with the `skipOpenApiSpecificationValidation` property.

### Validating only the request or only the response

By default, both the request and the response are validated against the OpenAPI specification.
If a test intentionally sends an invalid request or returns an intentionally invalid response,
you can disable one of the two checks for that test:

```php
// Validate the response while allowing the request to be intentionally invalid.
$this->validateOpenApiResponseOnly();

// Validate the request only, skipping the response validation.
$this->validateOpenApiRequestOnly();