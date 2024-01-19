<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Districts extends Model
{
    use HasFactory;

    protected $primaryKey = 'distID';

    protected $table = 'districts';
    // public $incrementing = true;
    // protected $keyType = 'int';

    public function divisions()
    {
        return $this->hasOne(Divisions::class, 'divID', 'fk_division_id');
    }
}
