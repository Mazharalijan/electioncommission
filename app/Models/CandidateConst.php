<?php

namespace App\Models;

use App\Models\Admin\Candidate;
use App\Models\Symbol;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CandidateConst extends Model
{
    use HasFactory;

    protected $table = 'candidate_const';

    protected $primaryID = 'CCID';

    public function candidate()
    {
        return $this->hasOne(Candidate::class, 'candidateID', 'fk_candidate_id');
    }
    public function symbols()
    {
        return $this->hasOne(Symbol::class, 'PartySymbolID', 'fk_symbol_id');
    }

    public function seats()
    {
        return $this->hasOne(SeatType::class, 'seatID', 'fk_seat_id');
    }

    public function districts()
    {
        return $this->hasOneThrough(Districts::class, SeatType::class, 'seatID', 'distID', 'fk_seat_id', 'fk_district_id');
    }
    public function votes()
    {
        return $this->hasManyThrough(Votes::class, Candidates::class, 'candidateID', 'voteID', 'candidateID', 'fk_candidate_id');
    }
}
