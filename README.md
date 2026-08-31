# OpenApi Testing

This package allows you to integrate the specification validation into the flow of tests.

## Installation

> **Note**  
> This package is mostly intended to be used as part of an API test suite.

Via Composer:

```shell
composer require --dev rebzya/openapi-testing
```

## Usage

You can use `\Rebzya\OpenApiTesting\Concerns\HasSpecificationAssertion` or `\Rebzya\OpenApiTesting\Concerns\ValidateOpenApiSpecification`
just add one of them into your TestCase class.

### HasSpecificationAssertion

The `HasSpecificationAssertion` trait adds the `assertSpecification` method
into your TestCase class, this may be necessary if you need to check one specific case.

### ValidateOpenApiSpecification

The `ValidateOpenApiSpecification` trait integrates into the `call` method
which will allow your application to validate specification in the flow of tests.


> **Note**  
> If for some reason there is no need to validate the specification disable it in your test method with the `skipOpenApiSpecificationValidation` method
> or disable it globally for the Test class by the `skipOpenApiSpecificationValidation` property.
