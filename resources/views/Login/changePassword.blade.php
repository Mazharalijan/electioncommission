@extends("Layout.layout3")
@section("content")
<center>
    <div style="width:36%; margin-left:-5%;">
        @include("Layout.alert")
    </div>
</center>

<div class="form-content">
    <div class="container-form">
        <form class="mb-3 login-input forms" method="POST" action="{{ route('postChangePassword') }}">
            @csrf
        <h3 class="form-heading">Change Account Password</h3>
        <div class="form-group">
          <label class="label-form" for="">Current Password</label>
          <input
            class="password-inputs"
            type="password"
            id="input1"
            name="currentPassword"
          />
          @error("currentPassword")
            <p class="text-danger">{{ $message }}</p>
          @enderror
        </div>
        <div class="form-group">
          <label class="label-form" for="">New Password</label>
          <input
            class="password-inputs"
            type="password"
            id="input2"
            name="password"
          />

          @error("password")
            <p class="text-danger">{{ $message }}</p>
        @enderror
        </div>
        <div class="form-group">
          <label class="label-form" for="">Confirm Password</label>
          <input
            class="password-inputs"
            type="password"
            id="input3"
            name="password_confirmation"
          />
          @error("password_confirmation")
            <p class="text-danger">{{ $message }}</p>
        @enderror
        </div>
        <button style="background-color: green;" type="submit">Change</button>
      </form>
    </div>
</div>
@endsection
