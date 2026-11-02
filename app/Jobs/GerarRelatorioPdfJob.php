<?php

namespace App\Jobs;

use App\Models\VwRelatorioLivro;
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

        // Busca os dados da VIEW MySQL
        $dados = VwRelatorioLivro::all();

        // Compila o PDF
        $pdf = Pdf::loadView('reports.livros_pdf', compact('dados'))
            ->setPaper('a4', 'landscape');

        // Salva o PDF compilado na pasta storage/app/public/relatorios/
        Storage::disk('public')->put('relatorios/relatorio-livros.pdf', $pdf->output());

        Log::info('Relatório PDF gerado com sucesso no Redis Queue e salvo em disk!');
    }
}
