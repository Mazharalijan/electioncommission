CREATE VIEW votesview AS
SELECT votes.voteID,districts.districtName,divisions.divName,votes.votes,candidate.candidateName,parties.partyName,parties.partyCode,party_symbol.symbol,party_symbol.symbolImage,seat_types.seatCode,seat_types.seatType,districts.distID,divisions.divID,seat_types.seatID,parties.partyID FROM `votes`
INNER JOIN candidate_const ON (candidate_const.fk_candidate_id = votes.fk_candidate_id AND candidate_const.fk_seat_id
= votes.fk_seat_id)
INNER JOIN seat_types ON seat_types.seatID = candidate_const.fk_seat_id
INNER JOIN party_symbol ON party_symbol.PartySymbolID = candidate_const.fk_symbol_id
INNER JOIN candidate ON candidate.candidateID = candidate_const.fk_candidate_id
INNER JOIN parties ON parties.partyID = party_symbol.fk_party_id
INNER JOIN districts ON districts.distID = seat_types.fk_district_id
INNER JOIN divisions ON divisions.divID = districts.fk_division_id;
