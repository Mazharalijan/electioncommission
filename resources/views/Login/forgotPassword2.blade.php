<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&family=Lato:ital,wght@1,300&family=Poppins:ital,wght@0,400;0,500;1,300;1,600&family=Roboto:ital,wght@0,300;0,500;1,300;1,500&display=swap"
      rel="stylesheet"
    />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
      integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
      crossorigin="anonymous"
      referrerpolicy="no-referrer"
    />
    <link rel="stylesheet" href="{{ asset('assest/css/signin.css') }}" />
    <title>signin</title>
  </head>
  <body>
    <nav>
      <div class="container">
        <div class="logo">
          <img src="{{ asset('assest/images/image 1.png') }}" alt="" />
        </div>

        <div class="form">

          <div class="wrapper">
            @include("Layout.alert")
            <form method="POST" action="{{ route('forgotReset') }}">
                @csrf
            <h1 class="form-text">Reset Password</h1>

            <div class="input-group">
              <i class="fas fa-envelope input-icon"></i>
              <input class="input" type="email" name="email" value="{{ old('email') }}" placeholder="Email" />
              @error("email")
                  <p class="text-danger">{{ $message }}</p>
              @enderror
            </div>

            <div class="forgot-password">
            <a href="{{ route('login') }}">
                <h3 class="reset-password">Back to login</h3>
            </a>

            </div>
            <div class="sign-btn">
              <button class="btn">Reset</button>
            </div>
        </form>
          </div>

        </div>

      </div>
    </nav>
  </body>

</html>
