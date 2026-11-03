<?php

namespace App\Jobs;

use App\Models\Autor;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class GerarRelatorioPdfJob implements ShouldQueue
{
    use Queueable;

    public function __construct() {}

    public function handle(): void
    {
        Log::info('Iniciando geração assíncrona do PDF via Redis Queue...');

        // Busca os Autores agrupando com seus respectivos Livros e Assuntos
        $autores = Autor::with(['livros.assuntos'])
            ->orderBy('nome', 'asc')
            ->get();

        // Compila o PDF agrupado por Autor (A4 Retrato)
        $pdf = Pdf::loadView('reports.livros_pdf', compact('autores'))
            ->setPaper('a4', 'portrait');

        // Salva o PDF compilado na pasta storage/app/public/relatorios/
        Storage::disk('public')->put('relatorios/relatorio-livros.pdf', $pdf->output());

        Log::info('Relatório PDF gerado com sucesso no Redis Queue e salvo em disk!');
    }
}
