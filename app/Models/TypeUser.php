<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TypeUser extends Model
{
    protected $table = "type_users";

    protected $fillable = [
        "type",
        "created_at",
        "updated_at",
    ];

    public $timestamps = true;
}
