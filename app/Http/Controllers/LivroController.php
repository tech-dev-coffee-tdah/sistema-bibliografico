<?php

namespace App\Http\Controllers;

use App\DTOs\LivroDTO;
use App\Http\Requests\LivroRequest;
use App\Models\Assunto;
use App\Models\Autor;
use App\Models\Livro;
use App\Services\LivroService;
use Illuminate\Http\Request;

class LivroController extends Controller
{
    public function __construct(
        protected LivroService $livroService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $livros = Livro::with(['autores', 'assuntos'])
            ->when($search, function ($query, $search) {
                $query->where('Titulo', 'LIKE', "%{$search}%")
                    ->orWhere('Editora', 'LIKE', "%{$search}%")
                    ->orWhereHas('autores', function ($q) use ($search) {
                        $q->where('nome', 'LIKE', "%{$search}%");
                    })
                    ->orWhereHas('assuntos', function ($q) use ($search) {
                        $q->where('descricao', 'LIKE', "%{$search}%");
                    });
            })
            ->paginate(10);

        return view('livros.index', compact('livros', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $autores = Autor::orderBy('nome')->get();
        $assuntos = Assunto::orderBy('descricao')->get();
        return view('livros.create', compact('autores', 'assuntos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(LivroRequest $request)
    {
        $dto = LivroDTO::fromRequest($request);
        $livro = $this->livroService->criarLivro($dto);

        return redirect()->route('livros.index')
            ->with('success', "Livro \"{$livro->titulo}\" (#{$livro->id}) cadastrado com sucesso!");
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Livro $livro)
    {
        $autores = Autor::orderBy('nome')->get();
        $assuntos = Assunto::orderBy('descricao')->get();
        return view('livros.edit', compact('livro', 'autores', 'assuntos'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(LivroRequest $request, Livro $livro)
    {
        $dto = LivroDTO::fromRequest($request);
        $this->livroService->atualizarLivro($livro, $dto);

        return redirect()->route('livros.index')
            ->with('success', "Livro \"{$livro->titulo}\" (#{$livro->id}) atualizado com sucesso!");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Livro $livro)
    {
        // Captura o Título e o ID antes de deletar do banco
        $titulo = $livro->titulo;
        $id = $livro->id;

        $this->livroService->deletarLivro($livro);

        return redirect()->route('livros.index')
            ->with('success', "Livro \"{$titulo}\" (#{$id}) removido com sucesso!");
    }
}
