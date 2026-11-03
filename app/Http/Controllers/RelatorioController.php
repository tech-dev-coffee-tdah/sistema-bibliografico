<?php

namespace App\Http\Controllers;

use App\Jobs\GerarRelatorioPdfJob;
use App\Models\Autor;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class RelatorioController extends Controller
{
    /**
     * Geração Síncrona: Abre o PDF agrupado por Autor diretamente na aba do navegador.
     */
    public function gerarPdf()
    {
        // Busca os Autores agrupando com seus respectivos Livros e Assuntos
        $autores = Autor::with(['livros.assuntos'])
            ->orderBy('nome', 'asc')
            ->get();

        // Passa a variável $autores para a view Blade
        $pdf = Pdf::loadView('reports.livros_pdf', compact('autores'))
            ->setPaper('a4', 'portrait');

        return $pdf->stream('relatorio-livros-tjjud.pdf');
    }

    /**
     * Geração Assíncrona via Redis Queue: Processa em background sem travar o usuário.
     */
    public function gerarPdfAssincrono()
    {
        // Envia a tarefa para a fila do Redis
        GerarRelatorioPdfJob::dispatch();

        return redirect()->back()->with(
            'success',
            'A solicitação do relatório foi enviada para a fila do Redis! O PDF está sendo gerado em segundo plano.'
        );
    }
}
