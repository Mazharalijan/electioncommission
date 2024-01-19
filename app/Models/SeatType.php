<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Votes;
class SeatType extends Model
{
    use HasFactory;

    protected $primaryKey = 'seatID';

    protected $table = 'seat_types';
    // public $incrementing = true;
    // protected $keyType = 'int';

    public function votes(){
        return $this->hasMany(Votes::class, 'fk_seat_id', 'seatID');
    }
}
