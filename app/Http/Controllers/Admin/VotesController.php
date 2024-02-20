<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CandidateConst;
use App\Models\Districts;
use App\Models\Divisions;
use App\Models\Party;
use App\Models\SeatType;
use App\Models\Votes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Paginator;
use Carbon\Carbon;

class VotesController extends Controller
{
    public function index(Request $request)
    {
        $seatCode=null;
        $partyCode=null;
        $districtCode = null;
        $divisionCode = null;
        if($request->get("seatCode") != null){
            $seatCode = $request->get("seatCode");
            //dd($seatCode);
        }
        if($request->get("partyCode") != null){
            $partyCode = $request->get("partyCode");
            //dd($seatCode);
        }
        if($request->get("district") != null){
            $districtCode = $request->get("district");
            //dd($seatCode);
        }
        if($request->get("division") != null){
            $divisionCode = $request->get("division");
            //dd($seatCode);
        }

        $district =  explode(",",auth('admin')->user()->fk_district_id);

        $votes = Votes::with(['candidatesconst.candidate','candidatesconst.symbols.party','candidatesconst.seats', 'districts.divisions']);
            if($partyCode != null){
            $votes=$votes->whereHas('candidatesconst.symbols.party',function($query) use ($partyCode) {
                    $query->where('partyID',$partyCode);
                });
            }

            if(Session::get('role') == 'Operator'){
                if($seatCode != null){
                    if($districtCode != null){
                        $votes->whereHas('seats', function ($query) use ($district,$seatCode,$districtCode) {
                            $query->where('seatType', 'Provincial')
                            ->where('fk_district_id',$districtCode)

                            ->where('seatID',$seatCode);
                        });
                    }else{
                        $votes->whereHas('seats', function ($query) use ($district,$seatCode) {
                            $query->where('seatType', 'Provincial')
                            ->whereIn('fk_district_id',$district)
                            ->where('seatID',$seatCode);
                        });
                    }

                }else{
                    if($districtCode != null){
                        $votes->whereHas('seats', function ($query) use ($districtCode,$district) {
                            $query->where('seatType', 'Provincial')
                            ->where('fk_district_id',$districtCode)
                            ->whereIn('fk_district_id',$district);

                        });
                    }else{
                        $votes->whereHas('seats', function ($query) use ($district) {
                            $query->where('seatType', 'Provincial')
                            ->whereIn('fk_district_id',$district);

                        });
                    }

                }

            }else{
                if($seatCode != null){
                    if($districtCode != null){
                        $votes->whereHas('seats', function ($query) use ($seatCode,$districtCode) {
                            $query->where('seatType', 'Provincial')
                            ->where('seatID',$seatCode)
                            ->where('fk_district_id',$districtCode);
                        });

                    }else{
                        $votes->whereHas('seats', function ($query) use ($seatCode) {
                            $query->where('seatType', 'Provincial')
                            ->where('seatID',$seatCode);
                        });
                    }

            }else{
                if($districtCode != null){
                    $votes->whereHas('seats', function ($query)use($districtCode) {
                        $query->where('seatType', 'Provincial')
                        ->where('fk_district_id',$districtCode);
                    });
                }else{
                    $votes->whereHas('seats', function ($query) {
                        $query->where('seatType', 'Provincial');


                    });
                }

                }
            }

            if($divisionCode != null){
                $votes = $votes->whereHas('districts', function ($query) use ($divisionCode) {
                    $query->with(['divisions'])
                    ->where('fk_division_id',$divisionCode);
                });

            }else{
                $votes = $votes->whereHas('districts', function ($query) {
                    $query->with(['divisions']);
                });
            }


            $votes = $votes->orderBy('fk_seat_id', 'ASC');
            $seatsId = [];
            $districtsId = [];
            $partiesIds = [];
            $divisionsId = [];

            if($seatCode !=null || $partyCode != null || $districtCode != null || $divisionCode != null){
                $votes = $votes->get();
                foreach($votes as $vote){
                    foreach($vote->candidatesconst as $item){
                        if($item->symbols != null){
                            $record =  $item->symbols->fk_party_id;
                            array_push($partiesIds,$record);
                        }


                        array_push($divisionsId,$vote->districts->fk_division_id);
                    }
                    array_push($seatsId,$vote->fk_seat_id);
                    array_push($districtsId,$vote->seats->fk_district_id);


                }
            }else{
                $votes = $votes->paginate(10);

                $filterData = Votes::with(['candidatesconst.candidate','candidatesconst.symbols.party','candidatesconst.seats', 'districts.divisions']);

                    $filterData->whereHas('seats', function ($query) use ($district) {
                        $query->where('seatType', 'Provincial');
                        if(Session::get('role') == 'Operator'){
                        $query->whereIn('fk_district_id',$district);
                    }
                    });


                $filterData=$filterData->get();
                foreach($filterData as $vote){
                    foreach($vote->candidatesconst as $item){
                        if($item->symbols != null){
                            $record =  $item->symbols->fk_party_id;
                            array_push($partiesIds,$record);
                        }


                        array_push($divisionsId,$vote->districts->fk_division_id);
                    }
                    array_push($seatsId,$vote->fk_seat_id);
                    array_push($districtsId,$vote->seats->fk_district_id);


                }
            }







        $parties = Party::whereIn('partyID',$partiesIds)->get();
        $seats = SeatType::whereIn('seatID', $seatsId)->get();
        $districts = Districts::whereIn('distID', $districtsId)->get();
        $divisions = Divisions::whereIn('divID',$divisionsId)->get();


        //  return $divisions;
        $data = compact('votes','divisions','districts','seats','parties');

        return view('Operators.pkvotelist')->with($data);
    }

    public function naSeats(Request $request)
    {
        $seatCode=null;
        $partyCode=null;
        $districtCode = null;
        $divisionCode = null;
        if($request->get("seatCode") != null){
            $seatCode = $request->get("seatCode");
            //dd($seatCode);
        }
        if($request->get("partyCode") != null){
            $partyCode = $request->get("partyCode");
            //dd($seatCode);
        }
        if($request->get("district") != null){
            $districtCode = $request->get("district");
            //dd($seatCode);
        }
        if($request->get("division") != null){
            $divisionCode = $request->get("division");
            //dd($seatCode);
        }

        $district =  explode(",",auth('admin')->user()->fk_district_id);
        $votes = Votes::with(['candidatesconst.candidate','candidatesconst.symbols.party','candidatesconst.seats', 'districts.divisions']);
        if($partyCode != null){
        $votes=$votes->whereHas('candidatesconst.symbols.party',function($query) use ($partyCode) {
                $query->where('partyID',$partyCode);
            });
        }

        if(Session::get('role') == 'Operator'){
            if($seatCode != null){
                if($districtCode != null){
                    $votes->whereHas('seats', function ($query) use ($district,$seatCode,$districtCode) {
                        $query->where('seatType', 'National')
                        ->where('fk_district_id',$districtCode)

                        ->where('seatID',$seatCode);
                    });
                }else{
                    $votes->whereHas('seats', function ($query) use ($district,$seatCode) {
                        $query->where('seatType', 'National')
                        ->whereIn('fk_district_id',$district)
                        ->where('seatID',$seatCode);
                    });
                }

            }else{
                if($districtCode != null){
                    $votes->whereHas('seats', function ($query) use ($districtCode,$district) {
                        $query->where('seatType', 'National')
                        ->where('fk_district_id',$districtCode);

                    });
                }else{
                    $votes->whereHas('seats', function ($query) use ($district) {
                        $query->where('seatType', 'National')
                        ->whereIn('fk_district_id',$district);

                    });
                }

            }

        }else{
            if($seatCode != null){
                if($districtCode != null){
                    $votes->whereHas('seats', function ($query) use ($seatCode,$districtCode) {
                        $query->where('seatType', 'National')
                        ->where('seatID',$seatCode)
                        ->where('fk_district_id',$districtCode);
                    });

                }else{
                    $votes->whereHas('seats', function ($query) use ($seatCode) {
                        $query->where('seatType', 'National')
                        ->where('seatID',$seatCode);
                    });
                }

        }else{
            if($districtCode != null){
                $votes->whereHas('seats', function ($query)use($districtCode) {
                    $query->where('seatType', 'National')
                    ->where('fk_district_id',$districtCode);
                });
            }else{
                $votes->whereHas('seats', function ($query) {
                    $query->where('seatType', 'National');


                });
            }

            }
        }

        if($divisionCode != null){
            $votes = $votes->whereHas('districts', function ($query) use ($divisionCode) {
                $query->with(['divisions'])
                ->where('fk_division_id',$divisionCode);
            });

        }else{
            $votes = $votes->whereHas('districts', function ($query) {
                $query->with(['divisions']);
            });
        }


            $votes = $votes->orderBy('fk_seat_id', 'ASC');
            $seatsId = [];
            $districtsId = [];
            $partiesIds = [];
            $divisionsId = [];


            if($seatCode !=null || $partyCode != null || $districtCode != null || $divisionCode != null){
                $votes = $votes->get();
                foreach($votes as $vote){
                    foreach($vote->candidatesconst as $item){
                        if($item->symbols != null){
                            $record =  $item->symbols->fk_party_id;
                            array_push($partiesIds,$record);
                        }
                        array_push($divisionsId,$vote->districts->fk_division_id);
                    }
                    array_push($seatsId,$vote->fk_seat_id);
                    array_push($districtsId,$vote->seats->fk_district_id);


                }
            }else{
                $votes = $votes->paginate(10);
                $filterData = Votes::with(['candidatesconst.candidate','candidatesconst.symbols.party','candidatesconst.seats', 'districts.divisions']);

                    $filterData->whereHas('seats', function ($query) use ($district) {
                        $query->where('seatType', 'National');
                        if(Session::get('role') == 'Operator'){
                            $query->whereIn('fk_district_id',$district);
                    }
                    });


                $filterData = $filterData->get();
                foreach($filterData as $vote){
                    foreach($vote->candidatesconst as $item){
                        if($item->symbols != null){
                            $record =  $item->symbols->fk_party_id;
                            array_push($partiesIds,$record);
                        }
                        array_push($divisionsId,$vote->districts->fk_division_id);
                    }
                    array_push($seatsId,$vote->fk_seat_id);
                    array_push($districtsId,$vote->seats->fk_district_id);


                }
            }




        $parties = Party::whereIn('partyID',$partiesIds)->get();
        $seats = SeatType::whereIn('seatID', $seatsId)->get();
        $districts = Districts::whereIn('distID', $districtsId)->get();
        $divisions = Divisions::whereIn('divID',$divisionsId)->get();

        $data = compact('votes','divisions','districts','seats','parties');

        return view('Operators.navotelist')->with($data);
    }

    public function create()
    {
        $district = explode(",",auth('admin')->user()->fk_district_id);
        $pkseats = SeatType::with(['votes'])->whereDoesntHave('votes')->where('seatType', 'Provincial')->whereIn('fk_district_id', $district)->get();
        $naseats = SeatType::with(['votes'])->whereDoesntHave('votes')->where('seatType', 'National')->whereIn('fk_district_id', $district)->get();

        $data = compact('pkseats', 'naseats');

        return view('Operators.entervotes2')->with($data);
    }
    public function store(Request $request)
    {
        $status= auth('admin')->user()->status;
        if($status != 'Active'){
            $request->session()->flask('error',"Your status is closed you can't add any record now");
            return response()->json([
                'status' => false,
                'message' => "You can't add record now!",

            ]);
        }
        $validator = Validator::make($request->all(), [
            'divisions' => 'required',
            'district' => 'required',
            'seattype' => 'required',
            'seatcode' => 'required',
            'candidate' => 'required',
            'votes' => 'required',

        ]);
        if ($validator->passes()) {
            try {
                $data = [
                    'votes' => $request->votes,
                    'fk_candidate_id' => $request->candidate,
                    'fk_seat_id' => $request->seatcode,
                    'EUID' => Session::get('user_id'),
                    'created_at' => Carbon::now()
                ];
                Votes::create($data);

                return response()->json([
                    'status' => true,
                    'message' => 'Record inserted successfully!',
                ]);

            } catch (\Exception $error) {
                return response()->json([
                    'status' => false,
                    'message' => 'Record not inserted!',
                    //'error' => $error,
                ]);
            }
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }

    }
    public function update(string $id,Request $request){

        $validator = Validator::make($request->all(),[
            'votes' => 'required'
        ]);
        if($validator->fails()){
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
                'message' => 'Validation error'
            ]);
        }

        $vote = Votes::find($id);
        // apply logic that on update the sum of total entered votes not more than registered votes

        if(is_null($vote)){
            return response()->json([
                'status' => false,
                'data' => null,
                'message' => 'Record not found'
            ]);
        }
        $seatId = $vote->fk_seat_id;
         $totalVotes = SeatType::where('seatID', $seatId)
                                ->selectRaw('SUM(registeredMaleVotes + registeredFemaleVotes) as totalVotes')
                                ->value('totalVotes');

        $totalVotes = intval($totalVotes);
         $votesSum = Votes::where('fk_seat_id', $seatId)
                        ->whereNotIn('voteID', [$id])
                        ->selectRaw('SUM(votes) AS votestabletotal')
                        ->groupBy('fk_seat_id')
                        ->first();
        $votesSum = intval($votesSum->votestabletotal) + $request->votes;
        // return response([
        //     "totalregvotes" => $totalVotes,
        //     "insertedvotes" => $votesSum
        // ]);


        $data  = [
            'votes' => $request->votes,
            'UUID' => Session::get('user_id'),
            'updated_at' => Carbon::now(),
        ];
        try{

            if($votesSum > $totalVotes){
                $vote->update($data);
                $request->session()->flash('success','Record updated');

                return response()->json([
                    'status' => true,
                    'exced' => true,
                    'message' => 'Enter votes exced the registered votes'
                ]);

        }
            $vote->update($data);
            $request->session()->flash('success','Record updated');
            return response()->json([
                'status' => true,
                'data' => $vote,
                'message' => 'Record updated!'
            ]);
        }catch(\Exception $error){
            return response()->json([
                'status' => false,
                'error' => $error,
                'message' => 'some internal error'
            ]);
        }



    }
    public function edit(string $id, Request $request){
        $status= auth('admin')->user()->status;
        if($status != 'Active'){
            $request->session()->flask('error',"Your status is closed you can't update any record now");
            return response()->json([
                'status' => false,
                'message' => "You can't update record now!",

            ]);
        }
        $vote=Votes::with(['singlecandidatesconst.candidate', 'singlecandidatesconst.symbols.party','seats', 'districts.divisions'])
        ->whereHas('districts', function ($query) {
            $query->with(['divisions']);
        })->where('voteID',$id)->first();
        if(is_null($vote)){
            $request->session()->flask('error','Record not found!');
            return response()->json([
                'message' => 'No record found',
                'status' => false
            ]);

        }else{
            return response()->json([
                'data' => $vote,
                'status' => true,
                'message' => 'record found'
            ]);
        }
    }

    public function enterPkVotesDetails(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'pkcode' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors(),
                'status' => false,
            ]);
        }

        try {
            $candidate = CandidateConst::with(['candidate','seats','symbols.party', 'districts'])->where('fk_seat_id', $request->pkcode)->get();
            if (! is_null($candidate)) {
                //return redirect()->route('votes.test1');

                return response()->json([
                    'data' => $candidate,
                    'status' => true,
                ]);
            } else {
                return response()->json([
                    'data' => null,
                    'status' => true,
                ]);
            }

        } catch (\Exception $error) {
            return response()->json([
                'data' => null,
                'status' => false,
                'error' => $error,
            ]);
        }

    }

    public function submitpkvotesentry(Request $request)
    {
        // validation for array
        $data = [];
        $votesData = [];
        for ($i = 0; $i < count($request->candidateID); $i++) {
            $record = [
                'fk_candidate_id' => $request->candidateID[$i],
                'fk_seat_id' => $request->seatID,
                'fk_candidateconst_id' => $request->ccID[$i],
                'votes' => $request->votes[$i],
                'EUID' => Session::get('user_id'),
                'created_at'=> Carbon::now()
            ];
            array_push($data, $record);
            array_push($votesData,$request->votes[$i]);
        }
        try {
            $totalVotes = SeatType::where('seatID', $request->seatID)
                                ->selectRaw('SUM(registeredMaleVotes + registeredFemaleVotes) as totalVotes')
                                ->value('totalVotes');
            $intervotes = array_sum($votesData);
            $totalVotes = intval($totalVotes);

            if($totalVotes < $intervotes){
                Votes::insert($data);
                $request->session()->flash('success', 'Record entered successfully!');
            return response()->json([
                'message' => 'Entered votes access the registered votes!!',
                'status' => true,
                'intervotes' => $intervotes,
                'totalvotes' => $totalVotes
            ]);
            }else{
            Votes::insert($data);

            $request->session()->flash('success', 'Record entered successfully!');

            return response()->json([
                'message' => 'Record entered successfully!',
                'status' => true,
            ]);
        }
        } catch (\Exception $error) {
            $request->session()->flash('error', 'Record not entered!');

            return response()->json([
                'message' => 'Record not entered!',
                'status' => false,
                'error' => $error,
            ]);
        }
    }

    public function submitnavotesentry(Request $request)
    {
        // validation for array
        $data = [];
        $votesData = [];
        for ($i = 0; $i < count($request->candidateID); $i++) {
            $record = [
                'fk_candidate_id' => $request->candidateID[$i],
                'fk_seat_id' => $request->seatID,
                'fk_candidateconst_id' => $request->ccID[$i],
                'votes' => $request->votes[$i],
                'EUID' => Session::get('user_id'),
                'created_at'=> Carbon::now()
            ];
            array_push($data, $record);
            array_push($votesData,$request->votes[$i]);
        }
        try {
            $totalVotes = SeatType::where('seatID', $request->seatID)
                                ->selectRaw('SUM(registeredMaleVotes + registeredFemaleVotes) as totalVotes')
                                ->value('totalVotes');
            $intervotes = array_sum($votesData);
            $totalVotes = intval($totalVotes);
            if($totalVotes < $intervotes){
                Votes::insert($data);
            $request->session()->flash('success', 'Record entered successfully!');
            return response()->json([
                'message' => 'Entered votes access the registered votes!!',
                'status' => true,
                'intervotes' => $intervotes,
                'totalvotes' => $totalVotes

            ]);
            }else{
            Votes::insert($data);
            $request->session()->flash('success', 'Record entered successfully!');

            return response()->json([
                'message' => 'Record entered successfully!',
                'status' => true,
            ]);
        }
        } catch (\Exception $error) {
            $request->session()->flash('error', 'Record not entered!');

            return response()->json([
                'message' => 'Record not entered!',
                'status' => false,
                'error' => $error,
            ]);
        }
    }

    public function enterNaVotesDetails(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nacode' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors(),
                'status' => false,
            ]);
        }

        try {
            $candidate = CandidateConst::with(['candidate','seats','symbols.party', 'districts'])->where('fk_seat_id', $request->nacode)->get();
            if (! is_null($candidate)) {
                return response()->json([
                    'data' => $candidate,
                    'status' => true,
                ]);
            } else {
                return response()->json([
                    'data' => null,
                    'status' => true,
                ]);
            }

        } catch (\Exception $error) {
            return response()->json([
                'data' => null,
                'status' => false,
                'error' => $error,
            ]);
        }
    }



    public function getDivision()
    {
        $divisions = Divisions::orderBy('divName', 'ASC')->get();

        return response()->json($divisions);

    }

    public function getParty()
    {
        $party = Party::orderBy('partyName', 'ASC')->get();

        return response()->json($party);

    }

    public function getDistrict(Request $request)
    {
        $district = Districts::where('fk_division_id', $request->divId)->orderBy('districtName', 'ASC')->get();

        return response()->json($district);
    }

    public function getDistrict2()
    {
        $district = Districts::orderBy('districtName', 'ASC')->get();

        return response()->json($district);
    }

    public function getCandidate(Request $request)
    {
        $candidates = CandidateConst::with(['candidate', 'seats'])->whereHas('seats', function ($query) use ($request) {
            $query->where('seatID', $request->seattypeId);
        })
            ->whereHas('candidate', function ($query) {
                $query->orderBy('candidateName', 'ASC');
            })
            ->get();
        // $candidate = [];
        // foreach ($candidates as $row) {
        //     array_push($candidate, [
        //         $row->candidate->candidateID,
        //         $row->candidate->candidateName,
        //     ]);
        // }
        $candidates = $candidates->map(function ($row) {
            return [
                'candidateID' => $row->candidate->candidateID,
                'candidateName' => $row->candidate->candidateName,
                'seatCode' => $row->seats->seatCode,
            ];
        })->toArray();

        return response()->json($candidates);
    }

    public function getSeattype(Request $request)
    {
        $seattype = SeatType::where('seatType', $request->seattype)
            ->where('fk_district_id', $request->distID)
            ->orderBy('seatCode', 'ASC')->get();

        return response()->json($seattype);

    }
}
