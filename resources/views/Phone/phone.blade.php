<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ELC otp</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <div class="container mt-5" style="max-width: 550px">
        <div class="alert alert-danger" id="error" style="display: none;"></div>
        <div class="alert alert-success" id="successAuth" style="display: none;"></div>
        <div id="number-div">
        <h5>Verify your account with otp</h5>

        <form>

                &nbsp;
                <a href="" id="verify-email-button" onclick="VerifywithEmail()" class="btn btn-info mt-3" >Verify with Email </a>



        </form>
        </div>

        <div class="mb-5 mt-5" id="email-verfication-code-div">
            <h5>Enter verification code</h5>
            <div class="alert alert-success" id="successOtpAuth" style="display: none;"></div>
            <form>
                <input type="number" id="email-verification" class="form-control" placeholder="Email Verification code">
                <button type="button" id="email-verification-button" class="btn btn-danger mt-3" onclick="Emailverify()">Verify code</button>
            </form>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <!-- Firebase App (the core Firebase SDK) is always required and must be listed first -->
    {{--  <script src="https://www.gstatic.com/firebasejs/6.0.2/firebase.js"></script>  --}}
    <script>
        $.ajaxSetup({
            headers:{
                'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>

    <script type="text/javascript">

        function VerifywithEmail(){

            event.preventDefault();
            $.ajax({
                url:'{{ route("operator.checkEmail") }}',
                type:'post',
                data:{},
                dataType:'JSON',
                success:function(response){
                  if(response["status"] === true){
                    $("#verfication-code-div").hide();
                    $("#successAuth").text("Email sent");
                    $("#successAuth").show();
                    setTimeout(function(){
                        $("#successAuth").hide();
                    },3000)
                    $("#number-div").hide();
                    $("#email-verfication-code-div").show();
                  }else{
                    $("#error").text("Email not send");
                    $("#error").show();
                    setTimeout(function(){
                        $("#error").hide();
                    },3000)
                  }
                }
            }
            );

        }
        $(document).ready(function(){


            $("#email-verfication-code-div").hide();
        })


        window.onload = function () {

            render();
        };
        document.getElementById('email-verification').addEventListener('keydown', function(event) {
            var emailcode = $("#email-verification").val();


            if (event.key === 'Enter') {

                //var emailcode = document.getElementById('email-verification').value;
                event.preventDefault();

                $('#email-verification-button').prop('disabled',true);
                $('#email-verification').prop('disabled',true);

                // Check if the input value is not empty
                if (emailcode.trim() !== '') {
                    $.ajax({
                        url: '{{ route("operator.setotpsession") }}',
                        type: 'post',
                        data: { 'emailcode': emailcode },
                        dataType: 'JSON',
                        success: function(response) {
                            $('#email-verification-button').prop('disabled',false);
                            $('#email-verification').prop('disabled',false);
                            window.location.replace("{{ route('votes.pklist') }}");
                        },
                        error: function(error) {
                            // Handle error if needed
                            $('#email-verification').prop('disabled',false);
                            $('#email-verification-button').prop('disabled',true);
                            console.error('AJAX error:', error);
                        }
                    });
                }
            }
        });

        function Emailverify(){
            event.preventDefault();

            var emailcode = $("#email-verification").val();
            $('#email-verification-button').prop('disabled',true);
            $('#email-verification').prop('disabled',true);
            $.ajax({
                url:'{{ route("operator.setotpsession") }}',
                type:'post',
                data:{'emailcode':emailcode},
                dataType:'JSON',
                success:function(response){
                    $('#email-verification-button').prop('disabled',false);
                    $('#email-verification').prop('disabled',false);
                    window.location.replace("{{ route('votes.pklist') }}");
                },
                error:function(error){
                    $('#email-verification-button').prop('disabled',false);
                    $('#email-verification').prop('disabled',false);
                    $("#error").text("Otp does not match");
                        $("#error").show();
                }
            });
        }
    </script>
</body>
</html>
