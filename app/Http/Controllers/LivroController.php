<?php

namespace App\Http\Controllers;

use App\Dtos\LivroDTO;
use App\Http\Requests\LivroRequest;
use App\Models\Assunto;
use App\Models\Autor;
use App\Models\Livro;
use App\Services\LivroService;

class LivroController extends Controller
{
    public function __construct(
        protected LivroService $livroService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $livros = Livro::with(['autors', 'assuntos'])->latest()->paginate(10);
        return view('livros.index', compact('livros'));
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
        $this->livroService->criarLivro($dto);

        return redirect()->route('livros.index')
            ->with('success', 'Livro cadastrado com sucesso!');
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
            ->with('success', 'Livro atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Livro $livro)
    {
        $this->livroService->deletarLivro($livro);

        return redirect()->route('livros.index')
            ->with('success', 'Livro removido com sucesso!');
    }
}
