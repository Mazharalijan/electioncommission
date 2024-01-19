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
        <h5>Enter Your Registered Phone Number</h5>

        <form>
            <label>Phone Number:</label>
            <input type="text" id="number" class="form-control" placeholder="+92 ********">
            <div id="recaptcha-container" ></div>
            <div>
            <button type="button" id="sendotp-button" class="btn btn-primary mt-3" onclick="sendOTP();">Send OTP</button>
                &nbsp;
                <a href="" id="verify-email-button" onclick="VerifywithEmail()" class="btn btn-info mt-3" >Verify with Email </a>
            </div>


        </form>
        </div>

        <div class="mb-5 mt-5" id="verfication-code-div">
            <h5>Enter verification code</h5>
            <div class="alert alert-success" id="successOtpAuth" style="display: none;"></div>
            <form>
                <input type="text" id="verification" class="form-control" placeholder="Verification code">
                <button type="button" id="verification-button" class="btn btn-danger mt-3" onclick="verify()">Verify code</button>
            </form>
        </div>
        <div class="mb-5 mt-5" id="email-verfication-code-div">
            <h5>Enter verification code</h5>
            <div class="alert alert-success" id="successOtpAuth" style="display: none;"></div>
            <form>
                <input type="text" id="email-verification" class="form-control" placeholder="Verification code">
                <button type="button" id="email-verification-button" class="btn btn-danger mt-3" onclick="Emailverify()">Verify code</button>
            </form>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <!-- Firebase App (the core Firebase SDK) is always required and must be listed first -->
    <script src="https://www.gstatic.com/firebasejs/6.0.2/firebase.js"></script>
    <script>
        $.ajaxSetup({
            headers:{
                'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>
    <script>
        var firebaseConfig = {
            apiKey: "AIzaSyDAyecFtdbcEPzhfMTNCY2CBXS1vbd_Hmk",
            authDomain: "election-commission-7377d.firebaseapp.com",
            databaseURL: "https://PROJECT_ID.firebaseio.com",
            projectId: "election-commission-7377d",
            storageBucket: "election-commission-7377d.appspot.com",
            messagingSenderId: "14265871255",
            appId: "1:14265871255:web:a0e5af583a5a8df504b2d2"
        };
        firebase.initializeApp(firebaseConfig);
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
            $("#verfication-code-div").hide();
            $("#email-verfication-code-div").hide();
        })


        window.onload = function () {

            render();
        };

        function render() {

            window.recaptchaVerifier = new firebase.auth.RecaptchaVerifier('recaptcha-container');
            recaptchaVerifier.render();
        }

        document.getElementById('number').addEventListener('keydown', function(event) {
            if (event.key === 'Enter') {

                event.preventDefault();
                $('#number').prop('disabled',true);
                $('#senotp-button').prop('disabled',true);
                $('#email-verify-button').prop('disabled',true);

                var number = $("#number").val();
                var checknumber = "{{ Session::get('phoneNo') }}";
                if(number == checknumber){
                    event.preventDefault();


                $.ajax({
                    url:'{{ route("operator.checknumber") }}',
                    type:'post',
                    data:{'number':number},
                    dataType:'JSON',
                    success:function(response){

                        //response = JSON.parse(response);

                        if(response["status"] === 'success'){

                            firebase.auth().signInWithPhoneNumber(number, window.recaptchaVerifier).then(function (confirmationResult) {
                                window.confirmationResult = confirmationResult;
                                coderesult = confirmationResult;
                                console.log(coderesult);
                                $("#successAuth").text("Message sent");
                                $("#successAuth").show();
                                setTimeout(function(){
                                    $("#successAuth").hide();
                                },3000)
                                $("#number-div").hide();
                                $("#verfication-code-div").show();

                            }).catch(function (error) {
                                $("#error").text(error.message);
                                $("#error").show();
                                setTimeout(function(){
                                    $("#error").hide();
                                },3000)

                            });
                        }else if(response["status"] == 'notmatched'){
                            $("#error").text("Phone number not matched");
                            $("#error").show();

                            setTimeout(function(){
                                $("#error").hide();
                            },3000)
                        }else{
                            $("#error").text("Internal server error");
                            $("#error").show();
                            setTimeout(function(){
                                $("#error").hide();
                            },3000)

                        }
                    }
                });

                // ajax logic ends here
                }else{
                    $("#error").text("Phone number not matched");
                    $("#error").show();
                    setTimeout(function(){
                        $("#error").hide();
                    },3000)
                }

                // ajax logic starts here

            }
        });
        function sendOTP() {
            var number = $("#number").val();
            var checknumber = "{{ Session::get('phoneNo') }}";
            if(number == checknumber){
                event.preventDefault();


            $.ajax({
                url:'{{ route("operator.checknumber") }}',
                type:'post',
                data:{'number':number},
                dataType:'JSON',
                success:function(response){

                    //response = JSON.parse(response);

                    if(response["status"] === 'success'){

                        firebase.auth().signInWithPhoneNumber(number, window.recaptchaVerifier).then(function (confirmationResult) {
                            window.confirmationResult = confirmationResult;
                            coderesult = confirmationResult;
                            console.log(coderesult);
                            $("#successAuth").text("Message sent");
                            $("#successAuth").show();
                            setTimeout(function(){
                                $("#successAuth").hide();
                            },3000)
                            $("#number-div").hide();
                            $("#verfication-code-div").show();

                        }).catch(function (error) {
                            $("#error").text(error.message);
                            $("#error").show();
                            setTimeout(function(){
                                $("#error").hide();
                            },3000)

                        });
                    }else if(response["status"] == 'notmatched'){
                        $("#error").text("Phone number not matched");
                        $("#error").show();

                        setTimeout(function(){
                            $("#error").hide();
                        },3000)
                    }else{
                        $("#error").text("Internal server error");
                        $("#error").show();
                        setTimeout(function(){
                            $("#error").hide();
                        },3000)

                    }
                }
            });

            // ajax logic ends here
            }else{
                $("#error").text("Phone number not matched");
                $("#error").show();
                setTimeout(function(){
                    $("#error").hide();
                },3000)
            }

            // ajax logic starts here



        }
        document.getElementById('email-verification').addEventListener('keydown', function(event) {
            if (event.key === 'Enter') {
                var emailcode = document.getElementById('email-verification').value;
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
        document.getElementById('verification').addEventListener('keydown', function(event) {
            if (event.key === 'Enter') {


                event.preventDefault();
                $('#verification-button').prop('disabled',true);
                $('#verification').prop('disabled',true);
                var code = $("#verification").val();
                coderesult.confirm(code).then(function (result) {
                    var user = result.user;

                    $("#successOtpAuth").text("Auth is successful");
                    $("#successOtpAuth").show();

                    $.ajax({
                        url:'{{ route("operator.setotpsession") }}',
                        type:'post',
                        data:{},
                        dataType:'JSON',
                        success:function(response){
                            $('#verification-button').prop('disabled',false);
                            $('#verification').prop('disabled',false);
                                window.location.replace("{{ route('votes.pklist') }}");


                        },
                        error:function(error){
                            $('#verification-button').prop('disabled',false);
                            $('#verification').prop('disabled',false);
                            $("#error").text("Otp does not match");
                                $("#error").show();
                        }
                    });

                    //ajax code to set session
                }).catch(function (error) {
                    $('#verification-button').prop('disabled',false);
                    $('#verification').prop('disabled',false);
                    $("#error").text(error.message);
                    $("#error").show();
                    setTimeout(function(){
                        $("#error").hide();
                    },3000)
                });
            }

            });
        function verify() {
            event.preventDefault();
            var code = $("#verification").val();
            $('#verification-button').prop('disabled',true);
            $('#verification').prop('disabled',true);
            coderesult.confirm(code).then(function (result) {

                var user = result.user;

                $("#successOtpAuth").text("Auth is successful");
                $("#successOtpAuth").show();

                $.ajax({
                    url:'{{ route("operator.setotpsession") }}',
                    type:'post',
                    data:{},
                    dataType:'JSON',
                    success:function(response){
                        $('#verification-button').prop('disabled',false);
                        $('#verification').prop('disabled',false);
                            window.location.replace("{{ route('votes.pklist') }}");


                    },
                    error:function(error){
                        $('#verification-button').prop('disabled',false);
                        $('#verification').prop('disabled',false);
                        $("#error").text("Otp does not match");
                            $("#error").show();
                    }
                });

                //ajax code to set session
            }).catch(function (error) {
                $('#verification-button').prop('disabled',false);
                $('#verification').prop('disabled',false);
                $("#error").text(error.message);
                $("#error").show();
                setTimeout(function(){
                    $("#error").hide();
                },3000)
            });
        }
    </script>
</body>
</html>
