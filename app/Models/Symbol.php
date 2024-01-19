<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Symbol extends Model
{
    use HasFactory;

    protected $table = 'party_symbol';

    protected $primaryKey = 'PartySymbolID';
    // public $incrementing = true;
    // protected $keyType = 'int';

    public function party()
    {
        return $this->hasOne(Party::class, 'partyID', 'fk_party_id');
    }
}
