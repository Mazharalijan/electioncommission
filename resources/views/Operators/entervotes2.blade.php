@extends("Layout.layout3")
@section("content")
@include("Layout.alert")
<div class="table-text">
    <a
    onclick="pkseats()"
    id="pkseatsatag"
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
      onclick="naseats()"
      id="naseatsatag"
      style="
        text-decoration: none;
        color: black;
        text-align: center;
        border-bottom: none;
        margin-top: 2px;
        cursor: pointer;
      "
      >National Seats</a
    >
  </div>

  <div class="table-container" id="pkseatsid">
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
        <h3>New Provincial Records</h3>
        <div style="display: flex; align-items: center; gap: 2rem">
            <a href="{{ route('votes.pklist') }}">
          <button
            style="
              color: black;
              background-color: #fff;
              padding: 8px;
              border: 2px solid #f9fafb;
              border-radius: 3px;
              cursor: pointer;
            "
          >
            cancel
          </button>
        </a>
        </div>
      </div>
    </div>
    <form name="pkdropdownform" id="pkdropdownform">

    <div class="select-group">
      <label class="label-text" for="code">Select Seat Code</label>
      <select class="dropdown" name="pkcode" id="pkcode">
        <option value="">salect</option>
        @isset($pkseats)
            @foreach ($pkseats as $pk)
            <option value="{{ $pk->seatID }}">{{ $pk->seatCode }}</option>
            @endforeach
        @endisset

      </select>
      <p></p>
    </div>
    <button class="show-candidate">
      Show Candidates
      <i class="fas fa-arrow-right icon" style="margin-left: 5px"></i>
    </button>
      </form>
  </div>
  <div class="table-container" id="naseats">
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
          <h3>New National Records</h3>
          <div style="display: flex; align-items: center; gap: 2rem">
            <a href="{{ route('votes.nalist') }}">
            <button
              style="
                color: black;
                background-color: #fff;
                padding: 8px;
                border: 2px solid #f9fafb;
                border-radius: 3px;
                cursor: pointer;
              "
            >
              cancel
            </button>
            </a>
          </div>
        </div>
      </div>

      <form name="nadropdownform" id="nadropdownform">

      <div class="select-group">
        <label class="label-text" for="code">Select Seat Code</label>
        <select class="dropdown" name="nacode" id="nacode">
          <option value="">salect</option>
          @isset($naseats)
              @foreach ($naseats as $na)
              <option value="{{ $na->seatID }}">{{ $na->seatCode }}</option>
              @endforeach
          @endisset

        </select>
      </div>
      <button class="show-candidate">
        Show Candidates
        <i class="fas fa-arrow-right icon" style="margin-left: 5px"></i>
      </button>
  </form>

    </div>

    <div class="table-container" id="naseatsform">
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
          <h3>New National Records</h3>
        </div>
      </div>
      <form name="navotesentrytableform" id="navotesentrytableform">
      <table style="width: 100%; table-layout: fixed" id="natableid">
        <thead>
          <tr>
            <th>Candidate</th>
            <th>Symbol</th>
            <th>Total Votes</th>
          </tr>
        </thead>
        <tbody>

        </tbody>
      </table>
      <div class="buttons">
        {{--  <button class="btn-permanent" style="margin-top: 20px">
          Save permanently
        </button>  --}}
        <button class="btn-save" style="margin-top: 20px">Save</button>
      </div>
    </div>
  </form>

     {{--  pk seats form starts  --}}
    <div class="table-container" id="pkseatsform">
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
          <h3>New Provincial Records</h3>
        </div>
      </div>
      <form name="pkvotesentrytableform"  id="pkvotesentrytableform">
      <table style="width: 100%; table-layout: fixed" id="pkvotesentrytable">
        <thead>
          <tr>
            <th>Candidate</th>
            <th>Symbol</th>
            <th>Total Votes</th>
          </tr>
        </thead>
        <tbody>
        </tbody>
      </table>
      <div class="buttons">
        {{--  <button class="btn-permanent" style="margin-top: 20px">
          Save permanently
        </button>  --}}
        <button class="btn-save" style="margin-top: 20px; float:right;">Save</button>
      </div>
      </form>
    </div>

@endsection

@section("customJS")
    <script>

        $(document).ready(function(){

            $("#pkseatsid").show();
            $("#naseats").hide();
            $("#naseatsform").hide();
            $("#pkseatsform").hide();

        });
        function naseats(){
            $("#pkseatsid").hide();
            $("#naseats").show();
            $("#naseatsform").hide();
            $("#pkseatsform").hide();

            removePKBorderStyle();

        }
        function pkseats(){
            $("#pkseatsid").show();
            $("#naseats").hide();
            $("#naseatsform").hide();
            $("#pkseatsform").hide();
            removeNABorderStyle();
        }
        function removePKBorderStyle() {
            // Get the element by its ID
            var pkseatsatag = document.getElementById('pkseatsatag');
            var naseatsatag = document.getElementById('naseatsatag');

            // Remove the border-bottom style
            pkseatsatag.style.borderBottom = 'none';
            naseatsatag.style.borderBottom = '3px solid green';
            naseatsatag.style.color = 'green';
            pkseatsatag.style.color = 'black';
        }
        function removeNABorderStyle() {
            // Get the element by its ID
            var naseatsatag = document.getElementById('naseatsatag');
            var pkseatsatag = document.getElementById('pkseatsatag');

            // Remove the border-bottom style
            pkseatsatag.style.borderBottom = '3px solid green';
            naseatsatag.style.borderBottom = 'none';
            pkseatsatag.style.color = 'green';
            naseatsatag.style.color = 'black';
        }

        $("#pkdropdownform").submit(function(event){


            event.preventDefault();
            var formArray = $(this).serializeArray();

            $('button[type=submit]').prop('disabled',true);
            $.ajax({
                url:'{{ route("votes.pkdetails") }}',
                type:'post',
                data:formArray,
                dataType:'JSON',
                success:function(response){
                    $('button[type=submit]').prop('disabled',false);
                    if(response['status'] == true){


                        $('#pkvotesentrytable').empty();
                        $("#pkvotesentrytable").append(`<tr>
                            <th>Candidate</th>
                            <th>Symbol</th>
                            <th>Total Votes</th>
                          </tr>`);

                        $.each(response["data"], function(key, value) {

                            $('#pkvotesentrytable').append(`<tr>
                                <td>${value.candidate.candidateName}
                                    <input type='hidden' name='candidateID[]' value='${value.candidate.candidateID}'>
                                    <input type='hidden' name='seatID' value='${value.fk_seat_id}'>
                                </td>
                                <td>${value.symbols !== null ? '<img width="50px" height="45px" src="{{ url('/PartySymbol/') }}'+'/'+value.symbols.symbolImage+'" />'  : 'Null'}</td>
                                <td class="total-votes">
                                  <input
                                    class="input-form"
                                    type="number"
                                    name="votes[]"
                                    placeholder="Enter here"
                                    required
                                  />
                                </td>
                              </tr>`);

                        });


                        $("#pkseatsid").hide();
                        $("#pkseatsform").show();

                        $('#pkcode')
                        .siblings('p')
                        .html("");

                    }else{
                        var errors = response['errors'];
                        $(".error").removeClass("invalid-feedback").html('');
                        $("input[type='number'],select").removeClass("is-invalid");
                        $.each(errors, function(key,value){
                            $(`#${key}`).addClass('is-invalid')
                            .siblings('p')
                            .addClass('invalid-feedback')
                            .html(value);
                        })


                    }

                }
            })
        });
        $("#nadropdownform").submit(function(event){


            event.preventDefault();
            var formArray = $(this).serializeArray();

            $('button[type=submit]').prop('disabled',true);
            $.ajax({
                url:'{{ route("votes.Nadetails") }}',
                type:'post',
                data:formArray,
                dataType:'JSON',
                success:function(response){
                    $('button[type=submit]').prop('disabled',false);
                    if(response['status'] == true){

                        $('#natableid').empty();
                        $("#natableid").append(`<tr>
                            <th>Candidate</th>
                            <th>Symbol</th>
                            <th>Total Votes</th>
                          </tr>`);
                        $.each(response["data"], function(key, value) {

                            $('#natableid').append(`<tr>
                                <td>${value.candidate.candidateName}
                                    <input type='hidden' name='candidateID[]' value='${value.candidate.candidateID}'>
                                    <input type='hidden' name='seatID' value='${value.fk_seat_id}'>
                                </td>
                                <td>${value.symbols !== null ? '<img width="50px" height="45px" src="{{ url('/PartySymbol/') }}'+'/'+value.symbols.symbolImage+'" />'  : 'Null'}</td>
                                <td class="total-votes">
                                  <input
                                    class="input-form"
                                    type="number"
                                    name="votes[]"
                                    placeholder="Enter here"
                                    required
                                  />
                                </td>
                              </tr>`);

                        });
                        $("#naseats").hide();
                        $("#naseatsform").show();

                        $('#pkcode')
                        .siblings('p')
                        .html("");

                    }else{
                        var errors = response['errors'];
                        $(".error").removeClass("invalid-feedback").html('');
                        $("input[type='number'],select").removeClass("is-invalid");
                        $.each(errors, function(key,value){
                            $(`#${key}`).addClass('is-invalid')
                            .siblings('p')
                            .addClass('invalid-feedback')
                            .html(value);
                        })


                    }

                }
            })
        });

        $("#pkvotesentrytableform").submit(function(event){
            event.preventDefault();
            var formArray = $(this).serializeArray();

            $('button[type=submit]').prop('disabled', true);

            $.ajax({
                url: '{{ route("votes.pkseatsentry") }}',
                type: 'post',
                data: formArray,
                dataType: 'json',
                success: function (response) {
                    if(response["status"] === true){

                        window.location.replace("{{ route('votes.pklist') }}");

                    }
                    if(response["status"] === false && response['totalvotes'] < response['intervotes']){
                        alert("Entered Votes sum more than registered votes")
                        $("#pkseatsform").show();

                    }


                }
            });

        });

        $("#navotesentrytableform").submit(function(event){
            event.preventDefault();
            var formArray = $(this).serializeArray();
            //alert("Hello");
            $('button[type=submit]').prop('disabled', true);

            $.ajax({
                url: '{{ route("votes.naseatsentry") }}',
                type: 'post',
                data: formArray,
                dataType: 'JSON',
                success: function (response) {

                    if(response["status"] === true){

                        window.location.replace("{{ route('votes.nalist') }}");

                    }
                    if(response["status"] === false && response['totalvotes'] < response['intervotes']){
                        alert("Entered Votes sum more than registered votes")
                        $("#naseatsform").show();

                    }



                }
            });

        });

    </script>
@endsection



