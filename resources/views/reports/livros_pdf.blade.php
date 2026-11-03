<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Relatório de Livros por Autor - TJ-JUD</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            color: #333;
            margin: 20px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #0056b3;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 18px;
            color: #0056b3;
            text-transform: uppercase;
        }
        .header p {
            margin: 5px 0 0;
            font-size: 10px;
            color: #666;
        }
        .autor-section {
            margin-bottom: 20px;
            page-break-inside: avoid;
        }
        .autor-header {
            background-color: #f0f4f8;
            border-left: 4px solid #0056b3;
            padding: 6px 10px;
            font-size: 12px;
            font-weight: bold;
            color: #0056b3;
            margin-bottom: 8px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        table, th, td {
            border: 1px solid #cccccc;
        }
        th {
            background-color: #e9ecef;
            color: #333;
            font-weight: bold;
            text-align: left;
            padding: 6px;
            font-size: 10px;
        }
        td {
            padding: 6px;
            vertical-align: middle;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .no-records {
            text-align: center;
            font-style: italic;
            color: #777;
            padding: 8px;
        }
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            font-size: 9px;
            text-align: center;
            color: #888;
            border-top: 1px solid #ddd;
            padding-top: 5px;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>Sistema Bibliográfico TJ-JUD</h1>
        <p>Relatório Analítico de Livros Agrupados por Autor — Gerado em {{ date('d/m/Y H:i') }}</p>
    </div>

    @forelse($autores as $autor)
        <div class="autor-section">
            <div class="autor-header">
                AUTOR: {{ $autor->nome ?? $autor->Nome }}
            </div>

            <table>
                <thead>
                    <tr>
                        <th style="width: 6%;" class="text-center">ID</th>
                        <th style="width: 34%;">Título</th>
                        <th style="width: 18%;">Editora</th>
                        <th style="width: 8%;" class="text-center">Edição</th>
                        <th style="width: 8%;" class="text-center">Ano</th>
                        <th style="width: 12%;" class="text-right">Valor</th>
                        <th style="width: 14%;">Assunto(s)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($autor->livros as $livro)
                        <tr>
                            <td class="text-center">{{ $livro->CodL ?? $livro->id }}</td>
                            <td>{{ $livro->Titulo ?? $livro->titulo }}</td>
                            <td>{{ $livro->Editora ?? $livro->editora }}</td>
                            <td class="text-center">{{ $livro->Edicao ?? $livro->edicao }}</td>
                            <td class="text-center">{{ $livro->AnoPublicacao ?? $livro->ano_publicacao }}</td>
                            <td class="text-right">R$ {{ number_format($livro->Valor ?? $livro->valor, 2, ',', '.') }}</td>
                            <td>
                                @if($livro->assuntos->isNotEmpty())
                                    {{ $livro->assuntos->pluck('descricao')->implode(', ') }}
                                @else
                                    <em style="color: #999;">Sem assunto</em>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="no-records">Nenhum livro cadastrado para este autor.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @empty
        <p class="no-records">Nenhum autor encontrado no sistema.</p>
    @endforelse

    <div class="footer">
        TJ-JUD — Sistema Bibliográfico | Processado via Eloquent (Agrupamento por Autor)
    </div>

</body>
</html>
