

  @if(Session::has('success'))
  <label id="success">
    <input type="checkbox" class="alertCheckbox" autocomplete="off" />
    <div class="alert success">
      <span class="alertClose">X</span>
      <span class="alertText">{{ Session::get('success') }}
      <br class="clear"/></span>
    </div>
  </label>
  @endif

  @if(Session::has('error'))
  <label id="error">
    <input type="checkbox" class="alertCheckbox" autocomplete="off" />
    <div class="alert error">
      <span class="alertClose">X</span>
      <span class="alertText">{{ Session::get('error') }}
      <br class="clear"/></span>
    </div>
  </label>
  @endif


  <script>
    setTimeout(function(){
        $("#error").hide()
        $("#success").hide()
    },3000)
  </script>
