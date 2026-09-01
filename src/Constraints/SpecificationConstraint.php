<?php

namespace Rebzya\OpenApiTesting\Constraints;

use Illuminate\Support\Facades\File;
use Osteel\OpenApi\Testing\ValidatorInterface;
use PHPUnit\Framework\Constraint\Constraint;
use Throwable;

abstract class SpecificationConstraint extends Constraint
{
    public function __construct(
        private readonly string $specification,
        private readonly string $path,
        private readonly string $method,
    ) {}

    public static function make(string $specification, string $path, string $method): SpecificationConstraint
    {
        return match (static::getExtension($specification)) {
            'json' => new JsonSpecificationConstraint($specification, $path, $method),
            'yaml' => new YamlSpecificationConstraint($specification, $path, $method),
        };
    }

    protected static function getExtension(string $specification): string
    {
        if (File::isFile($specification)) {
            return File::extension($specification);
        }

        return json_validate($specification) ? 'json' : 'yaml';
    }

    public function toString(): string
    {
        return 'matches the OpenAPI specification';
    }

    abstract protected function validator(string $definition): ValidatorInterface;

    public function evaluate(mixed $other, string $description = '', bool $returnResult = false): ?bool
    {
        try {
            return $this->validator($this->specification)->validate($other, $this->path, $this->method);
        } catch (Throwable $e) {
            if ($returnResult) {
                return false;
            }

            $this->fail($other, trim($description . "\n" . $e->getMessage()));
        }
    }
}