<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Desativa a validação de CSRF exclusivamente para a suíte de testes
        $this->withoutMiddleware([ValidateCsrfToken::class]);
    }
}
