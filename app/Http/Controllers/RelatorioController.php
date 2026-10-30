<?php

namespace App\Http\Controllers;

use App\Models\VwRelatorioLivro;
use Barryvdh\DomPDF\Facade\Pdf;

class RelatorioController extends Controller
{
    public function gerarPdf()
    {
        $dados = VwRelatorioLivro::all();

        $pdf = Pdf::loadView('reports.livros_pdf', compact('dados'))
            ->setPaper('a4', 'landscape');

        return $pdf->stream('relatorio-livros-tjjud.pdf');
    }
}
