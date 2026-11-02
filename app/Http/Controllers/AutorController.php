<?php

namespace App\Http\Controllers;

use App\Http\Requests\AutorRequest;
use App\Models\Autor;
use Illuminate\Support\Facades\Cache;

class AutorController extends Controller
{
    public function index()
    {
        // Armazena a listagem em cache no Redis por 60 minutos (3600 segundos)
        $autores = Cache::remember('autores_index_list', 3600, function () {
            return Autor::withCount('livros')->orderBy('nome')->paginate(10);
        });

        return view('autores.index', compact('autores'));
    }

    public function store(AutorRequest $request)
    {
        $autor = Autor::create($request->validated());

        // Invalida o cache para que a nova listagem seja recalculada no próximo acesso
        Cache::forget('autores_index_list');

        return redirect()->route('autores.index')
            ->with('success', "Autor \"{$autor->nome}\" (#{$autor->id}) cadastrado com sucesso!");
    }

    public function update(AutorRequest $request, Autor $autore)
    {
        $autore->update($request->validated());

        // Invalida o cache
        Cache::forget('autores_index_list');

        return redirect()->route('autores.index')
            ->with('success', "Autor \"{$autore->nome}\" (#{$autore->id}) atualizado com sucesso!");
    }

    public function destroy(Autor $autore)
    {
        $nome = $autore->nome;
        $id = $autore->id;
        $autore->delete();

        // Invalida o cache
        Cache::forget('autores_index_list');

        return redirect()->route('autores.index')
            ->with('success', "Autor \"{$nome}\" (#{$id}) removido com sucesso!");
    }
}
