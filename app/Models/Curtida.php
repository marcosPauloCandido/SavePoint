<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Curtida extends Model
{
    public $timestamps = false;
    protected $table = 'curtidas';

    protected $primaryKey = 'idCurtidas';

    protected $fillable = [
        'idUsuario',
        'idJogo',
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
