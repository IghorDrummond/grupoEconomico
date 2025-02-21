<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bandeira extends Model
{
    protected $table = "bandeiras";

    protected $fillable = [
        "nome",
        "grupo_economico",
        "created_at",
        "updated_at",
    ];

    public $timestamps = true;
}
