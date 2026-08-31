<?php

namespace Rebzya\OpenApiTesting\Tests\Yaml;

use Rebzya\OpenApiTesting\Tests\TestCase;

class YamlTest extends TestCase
{
    public function openApiSpecification(): string
    {
        return __DIR__ . '/../docs/docs.yaml';
    }

    public function testStatus(): void
    {
        $this->getJson('/status')->assertOk();
    }
}