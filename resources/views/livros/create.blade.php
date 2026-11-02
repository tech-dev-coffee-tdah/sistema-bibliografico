@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold text-secondary"><i class="bi bi-book-half"></i> Novo Livro</h2>
    <a href="{{ route('livros.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Voltar</a>
</div>

<div class="card border-0 shadow-sm p-4">
    <form action="{{ route('livros.store') }}" method="POST">
        @include('livros._form')
    </form>
</div>
@endsection
