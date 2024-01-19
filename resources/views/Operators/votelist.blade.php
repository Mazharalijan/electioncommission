@extends("Layout.layout")
@section("content")

<section class="content-header">
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Voting List</h1>
            </div>
            @if(Session::get('role') == 'Operator')
                <div class="col-sm-6 text-right">
                    <a href="{{ route('votes.create') }}" class="btn btn-primary">New Record</a>
                </div>
            @endif

        </div>
    </div>
    <!-- /.container-fluid -->
</section>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Votes Detail</h4>

                    <div class="table-responsive">
                        <table class="table table-striped table-bordered zero-configuration">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Party</th>
                                    <th>Symbol</th>
                                    <th>Seat Type</th>
                                    <th>Seat Code</th>
                                    <th>District</th>
                                    <th>Division</th>
                                    <th>Votes</th>
                                    @if(Session::get('role') == 'Operator')
                                        <th>Action</th>
                                    @endif

                                </tr>
                            </thead>
                            <tbody>
                                @if(!is_null($votes))
                                    @foreach ($votes as $vote)
                                    <tr>
                                        <td>{{ $vote->candidates->candidateName }}</td>
                                        <td>{{ $vote->candidates->symbols->party->partyName }}</td>
                                        <td>{{ $vote->candidates->symbols->symbol }}</td>
                                        <td>{{ $vote->seats->seatType }}</td>
                                        <td>{{ $vote->seats->seatCode }}</td>

                                        <td>{{ $vote->districts->districtName }}</td>
                                        <td>{{ $vote->districts->divisions->divName }}</td>

                                        <td>{{ $vote->votes }}</td>
                                        @if(Session::get('role') == 'Operator')
                                        <td>
                                            <a href="#">
                                                <i class="fa fa-edit btn" style="color:#7571f9;"></i>
                                            </a>

                                        </td>
                                    @endif
                                    </tr>
                                    @endforeach

                                @else
                                <tr>
                                    <td colspan="5">No Record found</td>
                                </tr>
                                @endif


                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@section("customJS")

@endsection
