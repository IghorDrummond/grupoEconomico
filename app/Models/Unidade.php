<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Unidade extends Model
{
    protected $table = "unidades";

    protected $fillable = [ 
        'nome_fantasia', 
        'razao_social', 
        'cnpj', 
        'bandeira', 
        'created_at', 
        'updated_at'
    ];

    public $timestamps = true;
}
