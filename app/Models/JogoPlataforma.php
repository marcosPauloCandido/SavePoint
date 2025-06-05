<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JogoPlataforma extends Model
{
    protected $table = 'jogo_plataformas';

    protected $fillable = [
        'idJogo',       // FK para tabela jogos
        'idPlataforma', // FK para tabela plataformas
    ];

    public $timestamps = false; // se não tiver timestamps na tabela
}
