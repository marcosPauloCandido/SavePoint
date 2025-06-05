<?php

namespace App\Models;

use App\Models\Comentarios;
use Illuminate\Database\Eloquent\Model;

class Jogo extends Model
{
    protected $table = 'jogos'; // Tabela que o model vai gerenciar

    protected $primaryKey = 'id_jogo'; // avisa o Laravel que a PK é 'id_jogo'

    protected $fillable = [
        'titulo',
        'descricao',
        'imagem_url',
        'data_lancamento',
        'desenvolvedora',
        'distribuidora',
    ];
    public $timestamps = false; // Desabilita timestamps se não forem necessários

    public function curtidas()
{
    return $this->hasMany(\App\Models\Curtida::class, 'idJogo', 'id_jogo');
}

 public function comentarios()
 {
    return $this->hasMany(Comentarios::class, 'idJogo', 'id_jogo');
 }

public function plataformas()
{
    return $this->belongsToMany(Plataforma::class, 'jogo_plataformas', 'idJogo', 'idPlataforma');
}
}