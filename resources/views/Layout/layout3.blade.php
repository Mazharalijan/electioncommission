<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
      integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
      crossorigin="anonymous"
      referrerpolicy="no-referrer"
    />
    <link rel="stylesheet" href="{{ asset('assest/css/districtoperator3.css') }}" />
    <link rel="stylesheet" href="{{ asset('assest/css/districtoperator4.css') }}" />
    <link rel="stylesheet" href="{{ asset('assest/css/modal.css') }}" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Votes</title>
  </head>
  <body>
    <header class="header">
      <div class="logo">
        <img src="{{ asset('assest/images/image 1.png') }}" alt="Logo" />
        <div>
          <h3 style="color: green">Vote Counter</h3>
        </div>
      </div>
      <div class="header-icon">
        <i class="fas fa-user" style="color: black; margin-right: 10px"></i>
        <div><p style="color: black">{{ auth('admin')->user()->name }}</p></div>
        <i
          class="fas fa-caret-down dropdown-icon"
          style="color: black; margin-left: 4px"
          onclick="toggleDropdown()"
        ></i>
        <div class="dropdown-content" id="dropdownContent">

          <a href="{{ route('changePassword') }}">Change Password</a>
          <a href="{{ route('logout') }}">Logout</a>
        </div>
        <button id="hamburger-button" class="hamburger-button">
            <div class="hamburger-line"></div>
            <div class="hamburger-line"></div>
            <div class="hamburger-line"></div>
        </button>
      </div>
    </header>

    <main class="layout">
      <aside class="sidebar">
        <ul>
          <li>
            <i class="fas fa-book" style="color: black"></i>
            <a href="{{ route('votes.pklist') }}">Votting Details</a>
          </li>
          @if(Session::get('role') == 'Admin')
          <li>
            <i class="fas fa-book" style="color: black"></i>
            <a href="{{ route('operator.list') }}">Users Account</a>
          </li>
          @endif

        </ul>
      </aside>
      <div class="mobile-menu">
        <ul>

          <li>
            <i class="fas fa-book"></i>
            <a href="{{ route('votes.pklist') }}">Voting Details</a>
          </li>
          @if(Session::get('role') == 'Admin')
          <li>
            <i class="fas fa-user"></i>
            <a href="{{ route('operator.list') }}">User Accounts</a>
          </li>
          @endif

        </ul>
      </div>
      <div class="content">
        @yield("content")
      </div>
    </main>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script>
        $.ajaxSetup({
            headers:{
                'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
            }
        });
            function toggleDropdown() {
              var dropdownContent = document.getElementById('dropdownContent');
              dropdownContent.classList.toggle('show');
            }


            window.onclick = function (event) {
              if (!event.target.matches('.dropdown-icon')) {
                var dropdowns = document.getElementsByClassName('dropdown-content');
                for (var i = 0; i < dropdowns.length; i++) {
                  var openDropdown = dropdowns[i];
                  if (openDropdown.classList.contains('show')) {
                    openDropdown.classList.remove('show');
                  }
                }
              }
            };

            document.addEventListener('DOMContentLoaded', () => {
                const hamburgerbutton = document.querySelector('.hamburger-button');
                const mobilemenu = document.querySelector('.mobile-menu');
                hamburgerbutton.addEventListener('click', () => {
                  mobilemenu.classList.toggle('active');
                });
              });
    </script>

    @yield('customJS')
  </body>
</html>
