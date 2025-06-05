<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JogoUser extends Model {
    use HasFactory;

    protected $table = 'jogos_users';

    protected $primaryKey = 'idJogoUsers';

    public $timestamps = false;

    protected $fillable = [
        'idUsers',
        'idJogo',
        'idStatus',
        'nota',
    ];


    public function user() {
        return $this->belongsTo(User::class, 'idUsers', 'id');
    }

    public function jogo() {
        return $this->belongsTo(Jogo::class, 'idJogo');
    }

    public function status() {
        return $this->belongsTo(Status::class, 'idStatus');
    }

}