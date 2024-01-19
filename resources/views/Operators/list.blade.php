@extends("Layout.layout3")

@section("content")
@include("Layout.alert")
        <div class="table-text">
          <a
            style="
              color: green;
              border-bottom: 3px solid green;
              text-decoration: none;
              cursor: pointer;
            "
          >
            District operators
          </a>
          {{--  <a
            href=""
            style="
              text-decoration: none;
              color: black;
              text-align: center;
              margin-top: 2px;
            "
            >Center control Unit</a
          >  --}}
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
              <h3>District Operators</h3>
              <div style="display: flex; align-items: center; gap: 2rem">
                <div style="display: flex">
                  {{--  <input
                    class="search-input"
                    type="text"
                    placeholder="search by name, email or district"
                  />
                  <button class="search-btn">
                    <i class="fas fa-search icon-last" style="color: white"></i>
                  </button>  --}}
                </div>
                {{--  <a onclick="addOperator()">  --}}
                <button
                id="modalbtn"
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
                {{--  </a>  --}}
              </div>
            </div>
          </div>
          <table style="width: 100%">
            <thead>
              <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Phone No</th>
                <th>District</th>
                <th>Status</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
                @if(!is_null($operators))
                    @foreach ($operators as $operator)
                    <tr>
                        <td>{{ $operator->name }}</td>
                        <td>{{ $operator->email }}</td>
                        <td>{{ $operator->phoneNo }}</td>
                        @if($operator->districts == null)
                        <td></td>
                        @else
                        <td>{{ $operator->districts->districtName }}</td>
                        @endif

                        <td><span style="margin-left: 15px; ">{{ $operator->status }}</span></td>
                        <td><center>
                            <a href="#" onclick="updateoperator({{ $operator->id }})" style="text-decoration: none"><span class="icon" style="color: green; margin-left:-25px;"><i class="fas fa-edit"></i></span></a>
                        </center>

                        </td>

                    </tr>
                    @endforeach

                @else
                <tr>
                    <td colspan="5">No Record found</td>
                </tr>
                @endif

            </tbody>
          </table>
        </div>
      </div>

       {{-- add operator modal code starts here  --}}
       <div id="myModal" class="modal">
        <div class="modal-content">
          <div class="modal-header">
            <span class="close" style="size: 5px">&times;</span>
            <h2>New Operator</h2>
          </div>
          <div class="modal-body">
            <form name="addoperator" id="addoperator">
            <div class="input-fields">
                <label class="modal-label" for="dropdown"
                ><span style="color: red">*</span>District</label
              >
              <select id="dropdown" name="district" class="drop-down">
              </select>
              <p class="text-danger" id="districterror"></p>
              <label for="input1" class="modal-label">
                <span style="color: red; margin-left: 3px">*</span>Name</label
              >
              <input type="text" name="name" class="input-modal" id="name" />
              <p class="text-danger" id="nameerror"></p>
              <label for="input2" class="modal-label">
                <span style="color: red">*</span>Email</label
              >
              <input type="email" name="email" class="input-modal" id="email" />
              <p class="text-danger" id="emailerror"></p>
              <label for="input3" class="modal-label">
                <span style="color: red">*</span>Phone</label
              >
              <input type="number" name="phoneNo" class="input-modal" id="phoneNo" />
              <p class="text-danger" id="phoneNoerror"></p>
            </div>
          </div>
          <div class="modal-footer">
            {{--  <button id="cancelBtn" style="cursor: pointer" class="cncel">Cancel</button>  --}}
            <button id="createBtn" style="cursor: pointer" class="crate">Create</button>
          </div>
        </form>
        </div>
      </div>

       {{-- add operator  modal code ends here  --}}


       {{-- update operator modal code starts here  --}}
       <div id="myupdateModal" class="modal">
        <div class="modal-content">
          <div class="modal-header">
            <span class="close" id="closeupdatemodal" style="size: 5px">&times;</span>
            <h2>Update Operator</h2>
          </div>
          <div class="modal-body">
            <form name="updateoperatorform" id="updateoperatorform">
            <div class="input-fields">
                <label class="modal-label" for="dropdown"
                ><span style="color: red">*</span>District</label
              >
              <select id="updateDistrict" name="district" class="drop-down">
              </select>
              <label for="input1" class="modal-label">
                <span style="color: red; margin-left: 3px">*</span>Name</label
              >
              <input type="text" name="name" class="input-modal" id="updateName" /><br />
              <input type="hidden" name="operatorID" id="operatorID">
              <label for="input2" class="modal-label">
                <span style="color: red">*</span>Email</label
              >
              <input type="email" name="email" class="input-modal" id="updateEmail" /><br />
              <label for="input3" class="modal-label">
                <span style="color: red">*</span>Phone</label
              >
              <input type="number" name="phoneNo" class="input-modal" id="updatePhoneNo" />
            </div>
          </div>
          <div class="modal-footer">
            <button id="closeupdatemodal2" style="cursor: pointer" class="cncel">Cancel</button>
            <button id="updateBtn" style="cursor: pointer" class="crate">Update</button>
          </div>
        </form>
        </div>
      </div>

       {{-- update operator  modal code ends here  --}}

@endsection

@section("customJS")
<script>
    var updatemodal = document.getElementById('myupdateModal');
    $("#closeupdatemodal").on('click', function(){
       updatemodal.style.display = 'none';
    })
    $("#closeupdatemodal2").on('click', function(){
        updatemodal.style.display = 'none';
     })

      var modal = document.getElementById('myModal');

      var btn = document.getElementById('modalbtn');
      var span = document.getElementsByClassName('close')[0];
      //var cancelBtn = document.getElementById('cancelBtn');
      var createBtn = document.getElementById('createBtn');

      // Function to open modal
      btn.onclick = function () {
        // get district
        $.ajax({
            url: '{{ route("getDistrict2") }}',
            type: "GET",
            data:{},
            dataType: "json",
            success:function(response) {
                $('#dropdown').empty();
                $('#dropdown').append(`<option value="">Please select</option>`);
                $.each(response, function(key, value) {
                    $('#dropdown').append('<option value="'+ value["distID"] +'">'+ value["districtName"] +'</option>');
                });
             }
        })
        modal.style.display = 'block';
      };

      function updateoperator(id){
        var district;
        var url = '{{ route("operator.edit","ID") }}';
        url = url.replace('ID',id);
        // fetch operator record
        $.ajax({
            url: url,
            type:'GET',
            data:{},
            dataType:'json',
            success:function(res){
                $("#updateName").val(res.data.name);
                $("#updateEmail").val(res.data.email);
                $("#updatePhoneNo").val(parseInt(res.data.phoneNo));
                $("#operatorID").val(res.data.id);
                 district = res.data.fk_district_id;

            }
        });

        $.ajax({
            url: '{{ route("getDistrict2") }}',
            type: "GET",
            data:{},
            dataType: "json",
            success:function(response) {

                $('#updateDistrict').empty();

                $('#updateDistrict').append(`<option value="">Please select</option>`);
                $.each(response, function(key, value) {
                    $('#updateDistrict').append('<option value="'+ value["distID"] +'" ' + (value["distID"] === district ? 'selected' : '') + '>'+ value["districtName"] +'</option>');
                });
             }
        })
        updatemodal.style.display = 'block';
      }
      // Function to close modal
      function closeModal() {
        modal.style.display = 'none';
        updatemodal.style.display = 'none';
      }

      // Event listeners for closing modal
      span.onclick = closeModal;
      //cancelBtn.onclick = closeModal;

      // Event listener for create button (You can modify this function)
      createBtn.onclick = function () {
        // Perform actions on 'Create' button click
        // For example, you can collect data from input fields
        // and perform any necessary operations
        closeModal(); // Close modal after action completion
      };

      // Event listener to close modal when clicking outside modal
      window.onclick = function (event) {
        if (event.target == modal) {
          closeModal();
        }
        if (event.target == updatemodal) {
            closeModal();
          }
      };
$("#updateoperatorform").submit(function(event){
    event.preventDefault();
    var formArray = $(this).serializeArray();
    var id = formArray[2].value;
    var url = '{{ route("operator.update","ID") }}';
    url = url.replace("ID",id);
    $.ajax({
        url:url,
        type:'put',
        data:formArray,
        dataType:'JSON',
        success:function(response){
            //console.log(response)
            window.location.href = "{{ route('operator.list') }}";
        }
    })

})
      $("#addoperator").submit(function(event){
        event.preventDefault();
        var formArray = $(this).serializeArray();

            $('button[type=submit]').prop('disabled', true);

            $.ajax({
                url: '{{ route("operator.store") }}',
                type: 'post',
                data: formArray,
                dataType: 'JSON',
                success: function (response) {


                    if(response["status"] === true){
                        $("#nameerror").html('');
                        $("#emailerror").html('');
                        $("#districterror").html('');
                        $("#phoneNoerror").html('');
                        window.location.href = "{{ route('operator.list') }}";

                    }
                    if(response["status"] === false){
                        //console.log(response.errors.name)
                        $("#nameerror").html('');
                        $("#emailerror").html('');
                        $("#districterror").html('');
                        $("#phoneNoerror").html('');
                        $("#nameerror").html(response.errors.name);
                        $("#emailerror").html(response.errors.email);
                        $("#districterror").html(response.errors.district);
                        $("#phoneNoerror").html(response.errors.phoneNo);


                    }



                },
                error: function (jqXHR, exception) {
                    console.log("Something went wrong");

                }

            });
            modal.style.display = 'block';
      })
</script>


@endsection
