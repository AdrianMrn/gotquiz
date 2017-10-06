@extends('layouts.app')

@section('content')
<script>

  function ConfirmDelete()
  {
  var x = confirm("Are you sure you want to delete this user?");
  if (x)
    return true;
  else
    return false;
  }

</script>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Contest id {{ $contest->id }}</div><br/>
                <div class="panel-body">
                    {!! Form::open(['method' => 'PATCH','route' => ['contests.update', $contest->id],'style'=>'display:inline']) !!}
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>id</th>
                                <th>Winner user id</th>
                                <th>Start date/time</th>
                                <th>End date/time</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ $contest->id }}</td>
                                <td>{{ Form::input('text', 'winner', $contest->winner_id) }}</td>
                                <td>{{ Form::input('text', 'start', $contest->start) }}</td>
                                <td>{{ Form::input('text', 'end', $contest->end) }}</td>
                                <td>{{ Form::input('text', 'status', $contest->status) }}</td> <!-- future: change to dropdown? -->
                            </tr>
                        </tbody>
                    </table>
                    <p>Note: if you change the start and/or end dates, it can take up to a minute for the contest status and the homepage to reflect this change.</p>
                    
                    {!! Form::submit('Save', ['class' => 'btn btn-success']) !!}
                    {!! Form::close() !!}
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
