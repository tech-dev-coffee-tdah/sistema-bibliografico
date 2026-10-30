<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VwRelatorioLivro extends Model
{
    protected $table = 'vw_relatorio_livros';
    public $timestamps = false;
    protected $primaryKey = 'livro_id';
}
