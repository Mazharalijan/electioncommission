<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Party extends Model
{
    use HasFactory;

    protected $primaryKey = 'partyID';

    protected $table = 'parties';
    // public $incrementing = true;
    // protected $keyType = 'int';
}
