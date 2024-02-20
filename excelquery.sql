INSERT INTO `candidate_const` (`fk_candidate_id`,`fk_symbol_id`,`fk_seat_id`)
VALUES
(
    (SELECT candidate.candidateID FROM `candidate` WHERE candidate.candidateName = ''),
    (SELECT party_symbol.PartySymbolID FROM party_symbol WHERE party_symbol.symbol=''),
    (SELECT seat_types.seatID FROM seat_types WHERE seat_types.seatCode='')
);


INSERT INTO `party_symbol`(`fk_party_id`, `symbol`, `symbolImage`) VALUES
(
    (SELECT parties.partyID FROM parties WHERE parties.partyCode = ""),
    "",
    CONCAT(party_symbol.symbol,'.jpg')
);
