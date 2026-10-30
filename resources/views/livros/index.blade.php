@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold text-secondary"><i class="bi bi-book"></i> Cadastro de Livros</h2>
    <a href="{{ route('livros.create') }}" class="btn btn-primary shadow-sm"><i class="bi bi-plus-lg"></i> Novo Livro</a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Título</th>
                    <th>Editora</th>
                    <th>Edição</th>
                    <th>Ano</th>
                    <th>Valor</th>
                    <th>Autor(es)</th>
                    <th>Assunto(s)</th>
                    <th class="text-end">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse($livros as $livro)
                    <tr>
                        <td>{{ $livro->id }}</td>
                        <td class="fw-bold">{{ $livro->titulo }}</td>
                        <td>{{ $livro->editora }}</td>
                        <td>{{ $livro->edicao }}ª</td>
                        <td>{{ $livro->ano_publicacao }}</td>
                        <td class="text-success fw-bold">R$ {{ number_format($livro->valor, 2, ',', '.') }}</td>
                        <td>
                            @foreach($livro->autors as $autor)
                                <span class="badge bg-info text-dark me-1">{{ $autor->nome }}</span>
                            @endforeach
                        </td>
                        <td>
                            @foreach($livro->assuntos as $assunto)
                                <span class="badge bg-secondary me-1">{{ $assunto->descricao }}</span>
                            @endforeach
                        </td>
                        <td class="text-end">
                            <a href="{{ route('livros.edit', $livro) }}" class="btn btn-sm btn-outline-primary me-1"><i class="bi bi-pencil"></i></a>
                            <form action="{{ route('livros.destroy', $livro) }}" method="POST" class="d-inline" onsubmit="return confirm('Deseja realmente remover este livro?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center py-4 text-muted">Nenhum livro cadastrado.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($livros->hasPages())
        <div class="card-footer bg-white pt-3">
            {{ $livros->links() }}
        </div>
    @endif
</div>
@endsection
