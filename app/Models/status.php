<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    protected $table = 'status';
    protected $primaryKey = 'idStatus';

    protected $fillable = [
        'status'
    ];

    public $timestamps = false; // Se a tabela não tem as colunas created_at e updated_at
}
