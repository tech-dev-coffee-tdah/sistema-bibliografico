@csrf

<div class="row g-3">
    <div class="col-md-8">
        <label for="titulo" class="form-label fw-bold">Título <span class="text-danger">*</span></label>
        <input type="text" class="form-control @error('titulo') is-invalid @enderror" id="titulo" name="titulo" value="{{ old('titulo', $livro->titulo ?? '') }}" maxlength="40" required>
        @error('titulo')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4">
        <label for="editora" class="form-label fw-bold">Editora <span class="text-danger">*</span></label>
        <input type="text" class="form-control @error('editora') is-invalid @enderror" id="editora" name="editora" value="{{ old('editora', $livro->editora ?? '') }}" maxlength="40" required>
        @error('editora')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4">
        <label for="edicao" class="form-label fw-bold">Edição <span class="text-danger">*</span></label>
        <input type="number" class="form-control @error('edicao') is-invalid @enderror" id="edicao" name="edicao" value="{{ old('edicao', $livro->edicao ?? '') }}" min="1" required>
        @error('edicao')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4">
        <label for="ano_publicacao" class="form-label fw-bold">Ano de Publicação <span class="text-danger">*</span></label>
        <input type="text"
               inputmode="numeric"
               class="form-control @error('ano_publicacao') is-invalid @enderror"
               id="ano_publicacao"
               name="ano_publicacao"
               value="{{ old('ano_publicacao', $livro->ano_publicacao ?? '') }}"
               maxlength="4"
               placeholder="Ex: 2026"
               required>
        @error('ano_publicacao')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4">
        <label for="valor" class="form-label fw-bold">Valor (R$) <span class="text-danger">*</span></label>
        <div class="input-group">
            <span class="input-group-text bg-light text-muted">R$</span>
            <input type="text"
                   inputmode="decimal"
                   class="form-control @error('valor') is-invalid @enderror"
                   id="valor"
                   name="valor"
                   value="{{ old('valor', isset($livro) ? number_format($livro->valor, 2, ',', '.') : '') }}"
                   placeholder="0,00"
                   required>
        </div>
        @error('valor')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="autores" class="form-label fw-bold">Autor(es) <span class="text-danger">*</span></label>
        <select class="form-select @error('autores') is-invalid @enderror" id="autores" name="autores[]" multiple required style="height: 120px;">
            @foreach($autores as $autor)
                <option value="{{ $autor->CodAu }}"
                    {{ (in_array($autor->CodAu, old('autores', isset($livro) ? $livro->autors->pluck('CodAu')->toArray() : []))) ? 'selected' : '' }}>
                    {{ $autor->nome }}
                </option>
            @endforeach
        </select>
        <small class="text-muted">Mantenha Ctrl pressionado para selecionar múltiplos.</small>
        @error('autores')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="assuntos" class="form-label fw-bold">Assunto(s) <span class="text-danger">*</span></label>
        <select class="form-select @error('assuntos') is-invalid @enderror" id="assuntos" name="assuntos[]" multiple required style="height: 120px;">
            @foreach($assuntos as $assunto)
                <option value="{{ $assunto->CodAs }}"
                    {{ (in_array($assunto->CodAs, old('assuntos', isset($livro) ? $livro->assuntos->pluck('CodAs')->toArray() : []))) ? 'selected' : '' }}>
                    {{ $assunto->descricao }}
                </option>
            @endforeach
        </select>
        <small class="text-muted">Mantenha Ctrl pressionado para selecionar múltiplos.</small>
        @error('assuntos')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="d-flex justify-content-end gap-2 mt-4">
    <a href="{{ route('livros.index') }}" class="btn btn-outline-secondary">Cancelar</a>
    <button type="submit" class="btn btn-primary shadow-sm"><i class="bi bi-check-lg"></i> Salvar Livro</button>
</div>

{{-- Script Vanilla JS para controle estrito dos inputs --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const anoInput = document.getElementById('ano_publicacao');
    const valorInput = document.getElementById('valor');

    // 1. Permite APENAS números no Ano de Publicação (máximo 4 dígitos)
    if (anoInput) {
        anoInput.addEventListener('input', function (e) {
            e.target.value = e.target.value.replace(/\D/g, '').slice(0, 4);
        });
    }

    // 2. Permite APENAS números no Valor e aplica máscara monetária (duas casas decimais)
    if (valorInput) {
        valorInput.addEventListener('input', function (e) {
            let value = e.target.value.replace(/\D/g, '');
            if (!value) {
                e.target.value = '';
                return;
            }
            let floatVal = (parseInt(value, 10) / 100).toFixed(2);
            let parts = floatVal.split('.');
            parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            e.target.value = parts.join(',');
        });
    }
});
</script>
