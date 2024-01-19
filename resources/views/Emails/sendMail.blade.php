<html>
    <head>
        <title>Email</title>
    </head>
    <body>
        <h1>{{ $mailData['title'] }}</h1>
        <p>

            Please click the following link to create your account password
        </p>
       <a href="{{ route('linkopened',$mailData['body']) }}">Click Here</a> <br/>
        <h5>Thank you</h5>
    </body>
</html>
