<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Jogo;

class Comentarios extends Model
{
    protected $table = 'comentarios';

    protected $primaryKey = 'idComentarios';

    public $timestamps = false;

    protected $fillable = [
        'idUsuario',
        'idJogo',
        'texto',
    ];

    public function usuario()
    {
     return $this->belongsTo(User::class, 'idUsuario', 'id');
    }

    public function jogo()
    {
        return $this->belongsTo(Jogo::class, 'idJogo', 'id_jogo');

    }

}