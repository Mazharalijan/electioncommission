@extends("Layout.layout3")

@section("content")

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
                  <input
                    class="search-input"
                    type="text"
                    placeholder="search by name, email or district"
                  />
                  <button class="search-btn">
                    <i class="fas fa-search icon-last" style="color: white"></i>
                  </button>
                </div>

                <button
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
              </div>
            </div>
          </div>
          <table style="width: 100%">
            <thead>
              <tr>
                <th>District</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>peshwar</td>
                <td>osama</td>
                <td>khaanosama@gmail.com</td>
                <td>+923010093050</td>
                <td>
                    {{--  <center>  --}}
                        <span class="icon" style="margin-left: 25px; color:red;"><i class="fas fa-trash-alt"></i></span>
                        {{--  <span class="icon"><i class="fas fa-edit"></i></span>  --}}
                    {{--  </center>  --}}

                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>


@endsection

@section("customJS")

@endsection
