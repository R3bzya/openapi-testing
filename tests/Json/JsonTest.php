<?php

namespace Rebzya\OpenApiTesting\Tests\Json;

use Rebzya\OpenApiTesting\Tests\TestCase;

class JsonTest extends TestCase
{
    public function openApiSpecification(): string
    {
        return __DIR__ . '/../docs/docs.json';
    }

    public function testStatus(): void
    {
        $this->getJson('/status')->assertOk();
    }
}