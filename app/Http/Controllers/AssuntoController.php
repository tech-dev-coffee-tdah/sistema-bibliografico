<?php

namespace App\Http\Controllers;

use App\Http\Requests\AssuntoRequest;
use App\Models\Assunto;

class AssuntoController extends Controller
{
    public function index()
    {
        $assuntos = Assunto::withCount('livros')->orderBy('descricao')->paginate(10);
        return view('assuntos.index', compact('assuntos'));
    }

    public function store(AssuntoRequest $request)
    {
        $assunto = Assunto::create($request->validated());

        return redirect()->route('assuntos.index')
            ->with('success', "Assunto \"{$assunto->descricao}\" (#{$assunto->id}) cadastrado com sucesso!");
    }

    public function update(AssuntoRequest $request, Assunto $assunto)
    {
        $assunto->update($request->validated());

        return redirect()->route('assuntos.index')
            ->with('success', "Assunto \"{$assunto->descricao}\" (#{$assunto->id}) atualizado com sucesso!");
    }

    public function destroy(Assunto $assunto)
    {
        $descricao = $assunto->descricao;
        $id = $assunto->id;
        $assunto->delete();

        return redirect()->route('assuntos.index')
            ->with('success', "Assunto \"{$descricao}\" (#{$id}) removido com sucesso!");
    }
}
