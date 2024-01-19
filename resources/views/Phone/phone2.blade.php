<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&family=Lato:ital,wght@1,300&family=Poppins:ital,wght@0,400;0,500;1,300;1,600&family=Roboto:ital,wght@0,300;0,500;1,300;1,500&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
      integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
      crossorigin="anonymous"
      referrerpolicy="no-referrer"
    />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="{{ asset('assest/css/otp2.css') }}" />
    <title>otpform</title>
  </head>
  <body>
    <nav>
      <div class="container">
        <div class="logo">
          <img src="{{ asset('assest/images/image 1.png') }}" alt="" />
        </div>
        <div class="form">
          <div class="wrapper">
            <div>
            <h1 class="form-head">Enter OTP</h1>
            <p class="form-text">
              Enter the one-time password we sent to your email
              faisal******gmail.com
            </p>
            <div class="otp-fields">
              <input type="number" maxlength="1" />
              <input type="number" maxlength="1" />
              <input type="number" maxlength="1" />
              <input type="number" maxlength="1" />
              <input type="number" maxlength="1" />
              <input type="number" maxlength="1" />
            </div>

            <p>
              Not received OTP yet?
              <span style="margin-left: 10px; ">
                <a href="#" style="text-decoration: none; color: green;">Resend</a>
              </span>
            </p>
            </div>
          </div>
        </div>
      </div>
    </nav>
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
        function Emailverify(){
            var emailcode = $("#email-verification").val();
            $.ajax({
                url:'{{ route("operator.setotpsession") }}',
                type:'post',
                data:{'emailcode':emailcode},
                dataType:'JSON',
                success:function(response){
                    window.location.replace("{{ route('votes.list') }}");
                }
            });
        }
        function verify() {
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

                            window.location.replace("{{ route('votes.list') }}");


                    },
                    error:function(error){
                        $("#error").text("Otp does not match");
                            $("#error").show();
                    }
                });

                //ajax code to set session
            }).catch(function (error) {
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
