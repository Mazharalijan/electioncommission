@extends("Layout.layout3")
    @section("content")
@include("Layout.alert")
        <div class="table-text">
          <a
          onclick="pkSeats()"
            style="
              color: green;
              border-bottom: 3px solid green;
              text-decoration: none;
              cursor: pointer;
            "
          >
            Provincial Seats
          </a>
          <a

            onclick="naSeats()"
            style="
              text-decoration: none;
              color: black;
              text-align: center;
              margin-top: 2px;
              cursor: pointer;
            "
            >National Seats</a
          >
        </div>

        <div class="table-container">
          <div class="wrapper">
            <div
              style="
                display: flex;
                justify-content: space-between;
                /* background-color: white; */
                align-items: center;
                border-radius: 5px;
              "
            >
              <h3>Provincial Records</h3>
              <div style="display: flex; align-items: center; gap: 2rem">
                @if(Session::get('role') == 'Operator')
                <a href="{{ route('votes.create') }}">
                <button
                  style="
                    color: white;
                    background-color: green;
                    padding: 8px;
                    border: none;
                    cursor: pointer;
                  "
                >
                  + Add New
                </button>
            </a>
            @endif
              </div>
            </div>
          </div>
          @php
            $status = auth('admin')->user()->status;
        @endphp
          <table style="width: 100%">
            <thead>
                <form method="" action="{{ route('votes.pklist') }}">
                <tr>
                    <th>
                        @if(Request::get('partyCode') != null || Request::get('seatCode') != null || Request::get('district') != null || Request::get('division') != null)
                            <button type="button" onclick="redirectToPklist()">Reset</button>
                            @endif
                    </th>
                    <th colspan="">
                        <div class="select-group">
                        <select name="partyCode" style="width: 90%" id="" class="dropdown">
                            <option value="">select party {{ Request::get("partyCode") }}</option>
                            @if(!is_null($parties))
                            @foreach ($parties as $party)
                            <option value="{{ $party->partyID }}" {{ Request::get("partyCode") == $party->partyID ? 'selected' : '' }}>{{ $party->partyCode }}</option>
                            @endforeach

                        @endif
                        </select>
                        </div>
                    </th>
                    <th></th>
                    <th colspan="">
                        <div class="select-group">
                        <select name="seatCode" style="width: 90%" id="" class="dropdown">
                            <option value="">select Seat</option>
                            @if(!is_null($seats))
                            @foreach ($seats as $seat)
                            <option value="{{ $seat->seatID }}" {{ Request::get("seatCode") == $seat->seatID ? 'selected' : '' }}>{{ $seat->seatCode }}</option>
                            @endforeach

                        @endif
                        </select>
                        </div>
                    </th>
                    <th colspan="">

                        <div class="select-group">
                        <select name="district" style="width: 90%" id="" class="dropdown">
                            <option value="">select District</option>
                            @if(!is_null($districts))
                                @foreach ($districts as $dist)
                                <option value="{{ $dist->distID }}" {{ Request::get("district") == $dist->distID ? 'selected' : '' }}>{{ $dist->districtName }}</option>
                                @endforeach

                            @endif
                        </select>
                        </div>

                    </th>
                    <th colspan="">
                        @if(Session::get('role') != 'Operator')
                        <div class="select-group">

                        <select name="division" style="width: 90%" id="" class="dropdown">
                            <option value="">Select Division</option>
                            @if(!is_null($divisions))
                                @foreach ($divisions as $div)
                                <option value="{{ $div->divID }}" {{ Request::get("division") == $div->divID ? 'selected' : '' }}>{{ $div->divName }}</option>
                                @endforeach

                            @endif
                        </select>
                        </div>
                        @endif
                    </th>
                    @if(Session::get('role') == 'Operator')
                        <th></th>
                        @endif
                    <th>
                        <button type="submit" style="margin-top: -2px;" class="show-candidate">search</button>

                    </th>
                </tr>
            </form>
              <tr>
                <th>Name</th>
                <th>Party</th>
                <th>Symbol</th>
                <th>
                    <center>
                        Seat Code
                    </center>
                    </th>
                <th>
                    <center>
                        District
                    </center>
                    </th>
                <th>
                    <center>
                        Division
                    </center>
                    </th>
                <th>
                    <center>
                        Votes
                    </center>

                </th>
                @if(Session::get('role') == 'Operator')
                @if($status == 'Active')
                    <th>
                        <center>
                            Actions
                        </center>

                    </th>
                @endif

                @endif
              </tr>
            </thead>
            <tbody>
                @if(!is_null($votes))
                    @foreach ($votes as $vote)
                    @foreach ($vote->candidatesconst as $candidaterow)
                    <tr>

                        <td>{{ $candidaterow->candidate->candidateName }}</td>

                        @if($candidaterow->symbols == NULL)
                            <td><center>NULL</center> </td>
                            <td><center>NULL</center> </td>

                        @else
                        <td>{{ $candidaterow->symbols->party->partyName }}</td>
                        <td><center><img style="width: 40px; height:40px;" src="{{ url('PartySymbol/'.$candidaterow->symbols->symbolImage )}}" alt=""></center></td>
                       @endif


                        <td>
                            <center>
                            {{ $candidaterow->seats->seatCode }}
                            </center>
                        </td>






                        <td>
                            <center>
                            {{ $vote->districts->districtName }}
                            </center>
                        </td>
                        <td>
                            <center>
                            {{ $vote->districts->divisions->divName }}
                            </center>
                        </td>

                        <td>
                            <center>
                            {{ $vote->votes }}
                            </center>
                        </td>
                        @if(Session::get('role') == 'Operator')

                        @if($status == 'Active')
                        <td>
                            <center>

                                <span class="icon" onclick="editVotes({{ $vote->voteID }})" style="color:#008000; cursor: pointer;"><i class="fas fa-edit"></i></span>
                            </center>
                            </td>
                        @endif

                    @endif
                    </tr>
                    @endforeach
                    @endforeach

                @else
                <tr>
                    <td colspan="5">No Record found</td>
                </tr>
                @endif

            </tbody>
          </table>
          @if(Request::get("partyCode") != null || Request::get("seatCode") != null || Request::get("district") != null || Request::get("division") != null )
          @else
            @if ($votes->lastPage() > 1)
            <div class="pagination" style="margin-top: 2%;">
                <a href="{{ $votes->previousPageUrl() }}">&laquo;</a>

                <a href="{{ $votes->url(1) }}" >{{ 1 }}</a>
                <a href="#" style="pointer-events: none;">...</a>
                <a href="{{ $votes->url($votes->currentPage()) }}" class="active">{{ $votes->currentPage() }}</a>
                <a href="#" style="pointer-events: none;">...</a>
                <a href="{{ $votes->url($votes->lastPage()) }}">{{ $votes->lastPage() }}</a>
                <a href="{{ $votes->nextPageUrl() }}">&raquo;</a>
            </div>
            @endif
          @endif


        </div>
       {{--  modal starts here  --}}

       <div id="myModal" class="modal" style="height: 100%;">
        <div class="modal-content" style="height: 300px;">
          <div class="modal-header">
            <span class="close" style="size: 5px">&times;</span>
            <h2>New Operator</h2>
          </div>
          <div class="modal-body">
            <form name="updatevotes" id="updatevotes">
                <table width="100%">

                    <tr>
                        <th>Name</th>
                        <th>Party</th>
                        <th>Symbol</th>
                        <th>Seat Code</th>
                        <th>Votes</th>

                    </tr>
                    <tr>
                        <td id="name">Name</td>
                        <td id="party">Party</td>
                        <td id="symbol">Symbol</td>
                        <td id="seatcode">Seat Code</td>
                        <td>
                            <input type="number" required name="votes" class="input-modal" id="vote" />
                            <input type="hidden"  name="votesID"  id="votesID" />
                        </td>
                    </tr>

                </table>
          </div>
          <div class="modal-footer">
            {{--  <button id="cancelBtn" style="cursor: pointer" class="cncel">Cancel</button>  --}}
            <button id="createBtn" style="cursor: pointer" class="crate">Update</button>
          </div>
        </form>
        </div>
      </div>

    {{--  modal end here  --}}
    @endsection
    @section("customJS")
    <script>
        function pkSeats(){
            window.location.href ="{{ route('votes.pklist') }}";
        }
        function naSeats(){
            window.location.href ="{{ route('votes.nalist') }}";
        }
        var modal = document.getElementById('myModal');


      var btn = document.getElementById('modalbtn');
      var span = document.getElementsByClassName('close')[0];
      //var cancelBtn = document.getElementById('cancelBtn');
      var createBtn = document.getElementById('createBtn');

      function closeModal() {
        modal.style.display = 'none';

      }

      // Event listeners for closing modal
      span.onclick = closeModal;

        function editVotes(id){

            var url = '{{ route("votes.edit","ID") }}';
            url=url.replace("ID",id);

            $.ajax({
                url:url,
                type:'get',
                data:{},
                dataType:'json',
                success:function(response){
                    if(response["status"] === true){

                        document.getElementById('name').innerHTML =response.data.singlecandidatesconst.candidate.candidateName;
                        document.getElementById('party').innerHTML =response.data.singlecandidatesconst.symbols.party.partyName;
                        document.getElementById('symbol').innerHTML =`<img src="{{ url('PartySymbol/${response.data.singlecandidatesconst.symbols.symbolImage}') }}" width="40px" height="40px">`;
                        document.getElementById('seatcode').innerHTML =response.data.seats.seatCode;
                        $("#vote").val(response.data.votes);
                        $("#votesID").val(response.data.voteID);
                        modal.style.display = 'block';
                   // console.log(response)
                    }else{
                        alert("something went wrong")
                    }


                }
            })

        }
        $("#updatevotes").submit(function(event){
            event.preventDefault();
            var id = $("#votesID").val();
            var url = '{{ route("votes.update","ID") }}';
            url = url.replace("ID",id);
            var formArray = $(this).serializeArray();
            $('#createBtn').prop('disabled', true);


            $.ajax({
                url:url,
                type:'put',
                data:formArray,
                dataType:'json',
                success:function(response){


                     if(response["status"] === true && response["exced"] ===true){
                        $('#createBtn').prop('disabled', false);
                        alert('Enter Votes exceds the registered votes');
                        closeModal();
                        window.location.href = '{{ route("votes.pklist") }}';
                    } else if(response["status"] === true){
                        $('#createBtn').prop('disabled', false);
                        closeModal();
                        window.location.href = '{{ route("votes.pklist") }}';
                    }
                    else{
                        $('#createBtn').prop('disabled', false);
                        alert('Something went wront!');
                    }

                }
            })
        })

        function redirectToPklist() {
            var url = "{{ route('votes.pklist') }}";
            window.location.href = url;
        }

    </script>
    @endsection

