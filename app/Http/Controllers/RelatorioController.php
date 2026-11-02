<?php

namespace App\Http\Controllers;

use App\Jobs\GerarRelatorioPdfJob;
use App\Models\VwRelatorioLivro;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class RelatorioController extends Controller
{
    /**
     * Geração Síncrona: Abre o PDF diretamente na aba do navegador.
     */
    public function gerarPdf()
    {
        $dados = VwRelatorioLivro::all();

        $pdf = Pdf::loadView('reports.livros_pdf', compact('dados'))
            ->setPaper('a4', 'landscape');

        return $pdf->stream('relatorio-livros-tjjud.pdf');
    }

    /**
     * Geração Assíncrona via Redis Queue: Processa em background sem travar o usuário.
     */
    public function gerarPdfAssincrono()
    {
        // Envia a tarefa pesada para a fila do Redis
        GerarRelatorioPdfJob::dispatch();

        return redirect()->back()->with(
            'success',
            'A solicitação do relatório foi enviada para a fila do Redis! O PDF está sendo gerado em segundo plano.'
        );
    }
}
