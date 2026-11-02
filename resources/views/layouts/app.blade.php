<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>{{ config('app.name', 'TJ-JUD - Livros') }}</title>

        <!-- Bootstrap 5.3 CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">

        <!-- Bootstrap Icons 1.11 -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.2/font/bootstrap-icons.min.css">

        <!-- Custom CSS -->
        <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    </head>
    <body>
        <!-- Navbar com suporte oficial ao tema escuro do Bootstrap 5.3 (data-bs-theme="dark" + bg-dark) -->
        <nav class="navbar navbar-expand-lg bg-dark navbar-dark mb-4 py-3 shadow-sm" data-bs-theme="dark">
            <div class="container">
                <a class="navbar-brand d-flex align-items-center gap-2 fw-bold text-white" href="{{ route('livros.index') }}">
                    <i class="bi bi-bank2 text-primary fs-4"></i>
                    <span>TJ-JUD <span class="fw-light text-white-50">| Sistema Bibliográfico</span></span>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto gap-2">
                        <li class="nav-item">
                            <a class="nav-link text-white {{ request()->routeIs('livros.*') ? 'active fw-bold border-bottom border-primary border-2' : 'opacity-75' }}" href="{{ route('livros.index') }}">
                                <i class="bi bi-book me-1"></i> Livros
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white {{ request()->routeIs('autores.*') ? 'active fw-bold border-bottom border-primary border-2' : 'opacity-75' }}" href="{{ route('autores.index') }}">
                                <i class="bi bi-person-lines-fill me-1"></i> Autores
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white {{ request()->routeIs('assuntos.*') ? 'active fw-bold border-bottom border-primary border-2' : 'opacity-75' }}" href="{{ route('assuntos.index') }}">
                                <i class="bi bi-tags me-1"></i> Assuntos
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <main class="container pb-5">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @yield('content')
        </main>

        <!-- Modal Reutilizável de Confirmação de Exclusão -->
        <div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-labelledby="deleteConfirmModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow">
                    <div class="modal-header bg-danger bg-opacity-10 text-danger border-0">
                        <h5 class="modal-title fw-bold" id="deleteConfirmModalLabel">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i> Confirmar Exclusão
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                    </div>
                    <div class="modal-body p-4">
                        <p class="mb-1 text-dark fs-6">Tem certeza de que deseja remover o registro abaixo?</p>
                        <div class="p-3 bg-light rounded border my-3">
                            <strong id="deleteItemTitle" class="text-secondary d-block fs-6">---</strong>
                        </div>
                        <small class="text-muted">
                            <i class="bi bi-info-circle me-1"></i> Esta ação realizará a exclusão lógica do registro.
                        </small>
                    </div>
                    <div class="modal-footer bg-light border-0">
                        <button type="button" class="btn btn-secondary fw-semibold" data-bs-dismiss="modal">Cancelar</button>
                        <form id="deleteConfirmForm" method="POST" action="">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger fw-semibold shadow-sm">
                                <i class="bi bi-trash-fill me-1"></i> Sim, Excluir
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bootstrap 5.3 JS Bundle -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>

        <!-- Script Global de Feedback Visual e Loading nos Botões de Formulário -->
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // Intercepta o envio de todos os formulários da página
                const forms = document.querySelectorAll('form');

                forms.forEach(function (form) {
                    form.addEventListener('submit', function (e) {
                        // Se a validação nativa do formulário passar
                        if (form.checkValidity()) {
                            const submitBtn = form.querySelector('button[type="submit"]');

                            if (submitBtn && !submitBtn.classList.contains('no-loading')) {
                                // Guarda o texto/ícone original do botão
                                const originalText = submitBtn.innerHTML;

                                // Desabilita o botão para evitar envios duplicados
                                submitBtn.disabled = true;

                                // Adiciona o Spinner animado do Bootstrap 5
                                submitBtn.innerHTML = `
                                    <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                                    Processando...
                                `;
                            }
                        }
                    });
                });
            });
        </script>

        <!-- Script de Controle da Modal de Exclusão -->
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const deleteModal = document.getElementById('deleteConfirmModal');
                if (deleteModal) {
                    deleteModal.addEventListener('show.bs.modal', function (event) {
                        // Botão que acionou a modal
                        const button = event.relatedTarget;

                        // Extrai as informações dos atributos data-*
                        const actionUrl = button.getAttribute('data-bs-action');
                        const itemTitle = button.getAttribute('data-bs-title');

                        // Atualiza a action do formulário e o título dentro da modal
                        const form = deleteModal.querySelector('#deleteConfirmForm');
                        const titleSpan = deleteModal.querySelector('#deleteItemTitle');

                        form.setAttribute('action', actionUrl);
                        titleSpan.textContent = itemTitle;
                    });
                }
            });
        </script>
    </body>
</html>
