<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class passwordResetToken extends Model
{
    protected $table = "password_reset_tokens";

    protected $fillable = [
        "id",
        "email",
        "token", 
        "actived",
        "user_id",
        "created_at",
        "updated_at",
    ];

    public $timestamps = true;
}
