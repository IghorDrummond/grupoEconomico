<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class grupoEconomico extends Model
{
    protected $table = "grupo_economicos";

    protected $fillable = [
        'id',
        "nome",
        "created_at",
        "updated_at",
    ];

    public $timestamps = true;
}
