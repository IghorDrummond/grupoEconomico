<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Colaborador extends Model
{
    protected $table = "colaboradores";

    protected $fillable = [
        "nome",
        "email",
        "cpf",
        "unidade",
        "created_at",
        "updated_at",
    ];

    public $timestamps = true;
}
