<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pollingstation extends Model
{
    use HasFactory;

    protected $primaryKey = 'pollingStationId';

    protected $table = [
        'pollingStationName',
    ];
}
