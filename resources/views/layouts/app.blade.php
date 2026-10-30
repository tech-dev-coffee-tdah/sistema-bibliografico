<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TJ-JUD - Gestão do Acervo</title>
    <link href="[suspicious link removed]" rel="stylesheet">
    <link rel="stylesheet" href="[suspicious link removed]">
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4 shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ route('livros.index') }}"><i class="bi bi-book"></i> TJ-JUD Livros</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link text-white" href="{{ route('livros.index') }}">Livros</a></li>
                    <li class="nav-item"><a class="nav-link text-white" href="{{ route('autores.index') }}">Autores</a></li>
                    <li class="nav-item"><a class="nav-link text-white" href="{{ route('assuntos.index') }}">Assuntos</a></li>
                </ul>
                <a href="{{ route('relatorio.pdf') }}" class="btn btn-warning fw-bold"><i class="bi bi-file-earmark-pdf"></i> Relatório PDF</a>
            </div>
        </div>
    </nav>

    <main class="container">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </main>

    <script src="[suspicious link removed]"></script>
    <script src="[suspicious link removed]"></script>
    @stack('scripts')
</body>
</html>
