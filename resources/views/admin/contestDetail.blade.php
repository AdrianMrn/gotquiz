@extends('layouts.app_dashboard')

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
                    {{ Form::hidden('id', $contest->id) }}
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>id</th>
                                <th>Winner user id</th>
                                <th>Admin id</th>
                                <th>Start date/time</th>
                                <th>End date/time</th>
                                <th>Status</th>
                                <th>Daily allowed participations</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ $contest->id }}</td>
                                <td>{{ Form::input('text', 'winner', $contest->winner_id, array('class'=>'form-control')) }}</td>
                                <td>{{ Form::input('text', 'contest_admin_id', $contest->contest_admin_id, array('class'=>'form-control', 'required'=>'required')) }}</td>
                                <td>{{ Form::input('text', 'start', $contest->start, array('class'=>'form-control', 'required'=>'required')) }}</td>
                                <td>{{ Form::input('text', 'end', $contest->end, array('class'=>'form-control', 'required'=>'required')) }}</td>
                                <td>{{ Form::select('status', array('upcoming'=>'upcoming', 'running'=>'running', 'finished'=>'finished'), $contest->status) }}</td>
                                <td>{{ Form::input('text', 'participations_allowed_daily', $contest->participations_allowed_daily, array('class'=>'form-control', 'required'=>'required')) }}</td>
                            </tr>
                        </tbody>
                    </table>
                    <p>Note: if you change the start and/or end dates, it can take up to a minute for the contest status and the homepage to reflect this change.</p>
                    
                    {!! Form::submit('Save', ['class' => 'btn btn-success']) !!}
                    {!! Form::close() !!}

                    @if (count($errors) > 0)
                    <br/><br/>
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
