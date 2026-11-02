@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold text-dark mb-1"><i class="bi bi-people-fill text-primary"></i> Cadastro de Autores</h2>
        <p class="text-muted mb-0 small">Gestão de autores de publicações do sistema.</p>
    </div>
</div>

<!-- Card de Novo Autor -->
<div class="card shadow-sm border-0 mb-4 bg-white">
    <div class="card-body p-3">
        <form action="{{ route('autores.store') }}" method="POST" class="row g-2 align-items-center">
            @csrf
            <div class="col-md-9">
                <input type="text" name="nome" class="form-control @error('nome') is-invalid @enderror" placeholder="Nome do Autor (máx. 40 caracteres)" maxlength="40" required>
                @error('nome')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary w-100 shadow-sm fw-semibold">
                    <i class="bi bi-plus-lg"></i> Adicionar Autor
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Tabela de Autores -->
<div class="card main-card shadow-sm bg-white">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-custom table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-4" style="width: 10%;">ID</th>
                        <th style="width: 50%;">Nome do Autor</th>
                        <th style="width: 20%;">Livros Vinculados</th>
                        <th class="pe-4 text-end" style="width: 20%;">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($autores as $autor)
                        <tr>
                            <td class="ps-4 text-muted fw-bold">#{{ $autor->id }}</td>
                            <td><span class="fw-bold text-dark">{{ $autor->nome }}</span></td>
                            <td><span class="badge bg-light text-dark border">{{ $autor->livros_count }} livro(s)</span></td>
                            <td class="pe-4 text-end">
                                <div class="d-flex justify-content-end gap-1">
                                    <button type="button" class="btn btn-sm btn-outline-primary btn-action" data-bs-toggle="modal" data-bs-target="#editAutorModal{{ $autor->id }}" title="Editar">
                                        <i class="bi bi-pencil-fill"></i>
                                    </button>
                                    <button type="button"
                                            class="btn btn-sm btn-outline-danger btn-action"
                                            data-bs-toggle="modal"
                                            data-bs-target="#deleteConfirmModal"
                                            data-bs-action="{{ route('autores.destroy', $autor) }}"
                                            data-bs-title="Autor: {{ $autor->nome }} (#{{ $autor->CodAu }})"
                                            title="Excluir">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <!-- Modal Editar Autor -->
                        <div class="modal fade" id="editAutorModal{{ $autor->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content border-0 shadow">
                                    <form action="{{ route('autores.update', $autor) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-header bg-light">
                                            <h5 class="modal-title fw-bold">Editar Autor #{{ $autor->id }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <label class="form-label fw-bold">Nome do Autor</label>
                                            <input type="text" name="nome" class="form-control" value="{{ $autor->nome }}" maxlength="40" required>
                                        </div>
                                        <div class="modal-footer bg-light border-0">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                            <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-4 text-muted">Nenhum autor cadastrado.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($autores->hasPages())
        <div class="card-footer bg-white border-0 py-3">
            {{ $autores->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>
@endsection
