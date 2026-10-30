<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <title>Relatório Sintético de Livros - TJ-JUD</title>
        <style>
            body { font-family: sans-serif; font-size: 11px; margin: 20px; color: #333; }
            .header { text-align: center; margin-bottom: 25px; border-bottom: 2px solid #0d6efd; padding-bottom: 10px; }
            .header h2 { margin: 0; color: #0d6efd; text-transform: uppercase; }
            .header p { margin: 5px 0 0; color: #6c757d; font-size: 10px; }
            table { width: 100%; border-collapse: collapse; margin-top: 10px; }
            th, td { border: 1px solid #dee2e6; padding: 7px 9px; text-align: left; }
            th { background-color: #f8f9fa; color: #495057; font-weight: bold; }
            tr:nth-child(even) { background-color: #fcfcfc; }
            .text-right { text-align: right; }
            .footer { margin-top: 30px; text-align: right; font-size: 9px; color: #6c757d; }
        </style>
    </head>
    <body>

        <div class="header">
            <h2>TJ-JUD — Sistema de Gestão Bibliográfica</h2>
            <p>Relatório Agregado de Livros por Autor e Assunto (VIEW MySQL)</p>
        </div>

        <table>
            <thead>
                <tr>
                    <th style="width: 5%;">ID</th>
                    <th style="width: 25%;">Título</th>
                    <th style="width: 15%;">Editora</th>
                    <th style="width: 6%;">Ed.</th>
                    <th style="width: 8%;">Ano</th>
                    <th style="width: 11%;" class="text-right">Valor</th>
                    <th style="width: 15%;">Autor(es)</th>
                    <th style="width: 15%;">Assunto(s)</th>
                </tr>
            </thead>
            <tbody>
                @forelse($dados as $item)
                    <tr>
                        <td>{{ $item->livro_id }}</td>
                        <td><strong>{{ $item->titulo }}</strong></td>
                        <td>{{ $item->editora }}</td>
                        <td>{{ $item->edicao }}ª</td>
                        <td>{{ $item->ano_publicacao }}</td>
                        <td class="text-right">R$ {{ number_format($item->valor, 2, ',', '.') }}</td>
                        <td>{{ $item->autores }}</td>
                        <td>{{ $item->assuntos }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align: center; padding: 20px;">Nenhum registro encontrado na VIEW do banco de dados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="footer">
            Gerado em {{ date('d/m/Y H:i:s') }} | Processamento via Database VIEW (`vw_relatorio_livros`)
        </div>

    </body>
</html>
