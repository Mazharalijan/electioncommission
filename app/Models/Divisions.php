<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Divisions extends Model
{
    use HasFactory;

    protected $primaryKey = 'divID';

    protected $table = 'divisions';
    // public $incrementing = true;
    // protected $keyType = 'int';
}
