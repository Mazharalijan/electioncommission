<?php

namespace App\Models\Admin;

use App\Models\Districts;
use App\Models\Divisions;
use App\Models\Party;
use App\Models\SeatType;
use App\Models\Votes;
use App\Models\Symbol;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Candidate extends Model
{
    use HasFactory;

    protected $primaryKey = 'candidateID';

    protected $table = 'candidate';
    // public $incrementing = true;
    // protected $keyType = 'int';
// fill able table
    protected $fillable = [
        'candidateName',
        'fk_district_id',
        'fk_seattype_id',
        'fk_party_id',
        'EUID',
    ];

    public function SeatType()
    {
        return $this->hasOne(SeatType::class, 'seatID', 'fk_seattype_id');
    }

    public function districts()
    {
        return $this->hasOne(Districts::class, 'distID', 'fk_district_id');
    }

    public function party()
    {
        return $this->hasOne(Party::class, 'partyID', 'fk_party_id');
    }

    public function symbols()
    {
        return $this->hasOne(Symbol::class, 'PartySymbolID', 'fk_symbol_id');
    }

    public function division()
    {
        return $this->hasOneThrough(Divisions::class, Districts::class, 'distID', 'divID', 'fk_district_id', 'fk_division_id');
    }

}
