@extends('layouts.app')

@section('content')
<!-- Cabeçalho da Página -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold text-dark mb-1"><i class="bi bi-journal-bookmark-fill text-primary"></i> Acervo Bibliográfico</h2>
        <p class="text-muted mb-0 small">Gestão centralizada de livros, autores e assuntos vinculados.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('relatorio.pdf') }}" target="_blank" class="btn btn-outline-danger shadow-sm fw-semibold">
            <i class="bi bi-file-earmark-pdf-fill"></i> Gerar PDF
        </a>
        <a href="{{ route('livros.create') }}" class="btn btn-primary shadow-sm fw-semibold">
            <i class="bi bi-plus-lg"></i> Novo Livro
        </a>
    </div>
</div>

<!-- Cards de Resumo / KPI -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card card-kpi shadow-sm p-3 bg-white">
            <div class="d-flex align-items-center">
                <div class="kpi-icon bg-primary bg-opacity-10 text-primary me-3">
                    <i class="bi bi-book"></i>
                </div>
                <div>
                    <span class="text-muted small fw-semibold">Total de Livros</span>
                    <h4 class="fw-bold text-dark mb-0">{{ $livros->total() }}</h4>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-kpi shadow-sm p-3 bg-white">
            <div class="d-flex align-items-center">
                <div class="kpi-icon bg-success bg-opacity-10 text-success me-3">
                    <i class="bi bi-currency-dollar"></i>
                </div>
                <div>
                    <span class="text-muted small fw-semibold">Valor Médio</span>
                    <h4 class="fw-bold text-dark mb-0">R$ {{ number_format($livros->avg('valor') ?? 0, 2, ',', '.') }}</h4>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-kpi shadow-sm p-3 bg-white">
            <div class="d-flex align-items-center">
                <div class="kpi-icon bg-info bg-opacity-10 text-info me-3">
                    <i class="bi bi-people"></i>
                </div>
                <div>
                    <span class="text-muted small fw-semibold">Exibindo</span>
                    <h4 class="fw-bold text-dark mb-0">{{ $livros->count() }} itens</h4>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-kpi shadow-sm p-3 bg-white">
            <div class="d-flex align-items-center">
                <div class="kpi-icon bg-warning bg-opacity-10 text-warning me-3">
                    <i class="bi bi-layers"></i>
                </div>
                <div>
                    <span class="text-muted small fw-semibold">Página Atual</span>
                    <h4 class="fw-bold text-dark mb-0">{{ $livros->currentPage() }} de {{ $livros->lastPage() }}</h4>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tabela de Livros -->
<div class="card main-card shadow-sm bg-white">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-custom table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-4" style="width: 5%;">ID</th>
                        <th style="width: 25%;">Título</th>
                        <th style="width: 13%;">Editora</th>
                        <th style="width: 8%;">Edição</th>
                        <th style="width: 8%;">Ano</th>
                        <th style="width: 11%;">Valor</th>
                        <th style="width: 13%;">Autor(es)</th>
                        <th style="width: 14%;">Assunto(s)</th>
                        <th class="pe-4 text-end" style="width: 13%;">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($livros as $livro)
                        <tr>
                            <td class="ps-4 text-muted fw-bold">#{{ $livro->id }}</td>
                            <td>
                                <span class="fw-bold text-dark d-block">{{ $livro->titulo }}</span>
                            </td>
                            <td class="text-secondary">{{ $livro->editora }}</td>
                            <td><span class="badge bg-light text-dark border">{{ $livro->edicao }}ª ed.</span></td>
                            <td class="text-secondary">{{ $livro->ano_publicacao }}</td>
                            <td>
                                <span class="fw-bold text-success">R$ {{ number_format($livro->valor, 2, ',', '.') }}</span>
                            </td>
                            <td>
                                @forelse($livro->autors as $autor)
                                    <span class="badge rounded-pill me-1 mb-1 fw-semibold"
                                        style="background-color: #e0f2fe !important; color: #0369a1 !important; border: 1px solid #bae6fd;">
                                        {{ $autor->nome }}
                                    </span>
                                @empty
                                    <span class="text-muted small">Nenhum</span>
                                @endforelse
                            </td>
                            <td>
                                @forelse($livro->assuntos as $assunto)
                                    <span class="badge rounded-pill me-1 mb-1 fw-semibold"
                                        style="background-color: #f1f5f9 !important; color: #334155 !important; border: 1px solid #cbd5e1;">
                                        {{ $assunto->descricao }}
                                    </span>
                                @empty
                                    <span class="text-muted small">Nenhum</span>
                                @endforelse
                            </td>
                            <td class="pe-4 text-end">
                                <!-- Botões de Ação Alinhados na Horizontal -->
                                <div class="d-flex justify-content-end gap-1">
                                    <!-- Botão Modal Visualizar -->
                                    <button type="button" class="btn btn-sm btn-outline-info btn-action" data-bs-toggle="modal" data-bs-target="#modalLivro{{ $livro->id }}" title="Visualizar Detalhes">
                                        <i class="bi bi-eye-fill"></i>
                                    </button>

                                    <!-- Botão Editar -->
                                    <a href="{{ route('livros.edit', $livro) }}" class="btn btn-sm btn-outline-primary btn-action" title="Editar">
                                        <i class="bi bi-pencil-fill"></i>
                                    </a>

                                    <!-- Excluir (Abre a Modal Customizada do Bootstrap) -->
                                    <button type="button"
                                            class="btn btn-sm btn-outline-danger btn-action"
                                            data-bs-toggle="modal"
                                            data-bs-target="#deleteConfirmModal"
                                            data-bs-action="{{ route('livros.destroy', $livro) }}"
                                            data-bs-title="Livro: {{ $livro->titulo }} (#{{ $livro->Codl }})"
                                            title="Excluir">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <!-- Modal de Detalhes do Livro -->
                        <div class="modal fade" id="modalLivro{{ $livro->id }}" tabindex="-1" aria-labelledby="modalLivroLabel{{ $livro->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content border-0 shadow">
                                    <div class="modal-header bg-light">
                                        <h5 class="modal-title fw-bold text-dark" id="modalLivroLabel{{ $livro->id }}">
                                            <i class="bi bi-book-half text-primary me-2"></i> Detalhes do Livro #{{ $livro->id }}
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body p-4">
                                        <div class="mb-3">
                                            <span class="text-muted small d-block">Título do Livro</span>
                                            <h5 class="fw-bold text-dark mb-0">{{ $livro->titulo }}</h5>
                                        </div>

                                        <div class="row g-3 mb-3">
                                            <div class="col-6">
                                                <span class="text-muted small d-block">Editora</span>
                                                <span class="fw-semibold text-secondary">{{ $livro->editora }}</span>
                                            </div>
                                            <div class="col-3">
                                                <span class="text-muted small d-block">Edição</span>
                                                <span class="badge bg-light text-dark border">{{ $livro->edicao }}ª ed.</span>
                                            </div>
                                            <div class="col-3">
                                                <span class="text-muted small d-block">Ano</span>
                                                <span class="fw-semibold text-secondary">{{ $livro->ano_publicacao }}</span>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <span class="text-muted small d-block">Valor do Exemplar</span>
                                            <h4 class="fw-bold text-success mb-0">R$ {{ number_format($livro->valor, 2, ',', '.') }}</h4>
                                        </div>

                                        <hr class="my-3 text-muted opacity-25">

                                        <div class="mb-3">
                                            <span class="text-muted small d-block mb-1">Autor(es) Vinculado(s)</span>
                                            @forelse($livro->autors as $autor)
                                                <span class="badge rounded-pill me-1 mb-1 fs-6 p-2 fw-semibold"
                                                    style="background-color: #e0f2fe !important; color: #0369a1 !important; border: 1px solid #bae6fd;">
                                                    {{ $autor->nome }}
                                                </span>
                                            @empty
                                                <span class="text-muted small">Nenhum autor vinculado.</span>
                                            @endforelse
                                        </div>

                                        <div>
                                            <span class="text-muted small d-block mb-1">Assunto(s) Vinculado(s)</span>
                                            @forelse($livro->assuntos as $assunto)
                                                <span class="badge rounded-pill me-1 mb-1 fs-6 p-2 fw-semibold"
                                                    style="background-color: #f1f5f9 !important; color: #334155 !important; border: 1px solid #cbd5e1;">
                                                    {{ $assunto->descricao }}
                                                </span>
                                            @empty
                                                <span class="text-muted small">Nenhum assunto vinculado.</span>
                                            @endforelse
                                        </div>
                                    </div>
                                    <div class="modal-footer bg-light border-0">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                                        <a href="{{ route('livros.edit', $livro) }}" class="btn btn-primary">
                                            <i class="bi bi-pencil-fill me-1"></i> Editar Livro
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox display-6 d-block mb-2 text-secondary"></i>
                                Nenhum livro cadastrado no sistema.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($livros->hasPages())
        <div class="card-footer bg-white border-0 py-3">
            {{ $livros->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>
@endsection
