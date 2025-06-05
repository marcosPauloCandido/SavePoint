<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plataforma extends Model
{
    protected $table = 'plataformas';
    protected $primaryKey = 'idPlataformas';

    protected $fillable = ['plataforma'];

    public $timestamps = false;

    public function jogos()
    {
        return $this->belongsToMany(Jogo::class, 'jogo_plataformas', 'idPlataforma', 'idJogo');
    }
}
