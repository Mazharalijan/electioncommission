<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsersCodes extends Model
{
    use HasFactory;

    protected $table = 'users_codes';

    protected $primaryKey = 'code_id';

    protected $fillable = [
        'codes',
        'fk_user_id',
    ];
}
