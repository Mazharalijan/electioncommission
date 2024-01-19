<?php

namespace App\Models;

use App\Models\Admin\Candidate;
use App\Models\CandidateConst;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Votes extends Model
{
    use HasFactory;

    protected $primaryKey = 'voteID';

    protected $table = 'votes';

    protected $fillable = [
        'votes',
        'fk_candidate_id',
        'fk_seat_id',
        'EUID',
        'UUID',
        'updated_at',
        'created_at'

    ];

    public function Candidates()
    {
        return $this->hasOne(Candidate::class, 'candidateID', 'fk_candidate_id');
    }
    public function candidatesconst()
    {
        return $this->hasMany(CandidateConst::class, 'fk_candidate_id', 'fk_candidate_id');
    }
    public function singlecandidatesconst()
    {
        return $this->hasOne(CandidateConst::class, 'fk_candidate_id', 'fk_candidate_id');
    }
    public function seats()
    {
        return $this->hasOne(SeatType::class, 'seatID', 'fk_seat_id');
    }

    public function districts()
    {
        return $this->hasOneThrough(Districts::class, SeatType::class, 'seatID', 'distID', 'fk_seat_id', 'fk_district_id');
    }

    public function symbols()
    {
        return $this->hasOneThrough(Symbol::class, Candidate::class, 'candidateID', 'PartySymbolID', 'fk_candidate_id', 'fk_symbol_id');
    }

    public function divisions()
    {
        return $this->hasOne(Divisions::class, 'divID', 'fk_division_id');
    }

    // public function seat_types()
    // {
    //     return $this->hasMany(SeatType::class, Candidate::class, 'candidateID', 'seatID', 'fk_candidate_id', 'fk_seattype_id');
    // }

    // public function party()
    // {
    //     return $this->hasOneThrough(Party::class, Candidate::class, 'candidateID', 'partyID', 'fk_candidate_id', 'fk_party_id');
    // }

    // public function districts()
    // {
    //     return $this->hasOneThrough(Districts::class, Candidate::class, 'candidateID', 'distID', 'fk_candidate_id', 'fk_district_id');
    // }
}
