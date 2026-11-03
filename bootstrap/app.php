<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Isenta a validação de CSRF em todas as rotas APENAS no ambiente de testes
        $middleware->validateCsrfTokens(
            except: (env('APP_ENV') === 'testing' || ($_ENV['APP_ENV'] ?? '') === 'testing') ? ['*'] : []
        );
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
        );

        // Tratamento específico para erros de Banco de Dados (QueryException)
        $exceptions->render(function (QueryException $e, $request) {
            // Registra a query e o erro real nos logs internos de auditoria
            Log::error('Erro de Banco de Dados:', [
                'sql' => $e->getSql(),
                'code' => $e->getCode(),
                'message' => $e->getMessage()
            ]);

        // Retorna mensagem amigável sem expor a estrutura interna do SQL
            return redirect()->back()->with('error', 'Não foi possível concluir a operação devido a uma falha no banco de dados.');
        });

        // Tratamento para Registros Não Encontrados (ModelNotFoundException)
        $exceptions->render(function (ModelNotFoundException $e, $request) {
            return redirect()->route('livros.index')->with('error', 'O registro solicitado não foi localizado.');
        });
    })->create();
