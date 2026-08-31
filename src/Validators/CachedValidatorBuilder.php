<?php

namespace Rebzya\OpenApiTesting\Validators;

use Osteel\OpenApi\Testing\ValidatorBuilder;
use Osteel\OpenApi\Testing\ValidatorInterface;

class CachedValidatorBuilder
{
    /** @var array<string, ValidatorInterface> */
    private static array $validators = [];

    public static function fromYaml(string $definition): ValidatorInterface
    {
        return self::$validators[md5($definition)] ??= ValidatorBuilder::fromYaml($definition)->getValidator();
    }

    public static function fromJson(string $definition): ValidatorInterface
    {
        return self::$validators[md5($definition)] ??= ValidatorBuilder::fromJson($definition)->getValidator();
    }
}