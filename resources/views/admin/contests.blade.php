@extends('layouts.app_dashboard')

@section('content')
<script>

  function ConfirmDelete()
  {
  var x = confirm("Are you sure you want to delete this?");
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
                <div class="panel-heading">Contests</div><br/>
                <div class="container">
                    {!! Form::open(['method' => 'PATCH','route' => ['contests.update', 0],'style'=>'display:inline']) !!}
                    {!! Form::submit('Create a new contest', ['class' => 'btn btn-success']) !!}
                    {!! Form::close() !!}
                </div>
                <div class="panel-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>id</th>
                                <th>Winner user id</th>
                                <th>Admin id</th>
                                <th>Started</th>
                                <th>Ends</th>
                                <th>Status</th>
                                <th>Participations/day</th>
                                <th>Export participations</th>
                                <th>Edit</th>
                                <th>Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($contests as $contest)
                            <tr>
                                <td>{{ $contest->id }}</td>
                                <td>{{ $contest->winner_id }}</td>
                                <td>{{ $contest->contest_admin_id }}</td>
                                <td>{{ $contest->start }}</td>
                                <td>{{ $contest->end }}</td>
                                <td>{{ $contest->status }}</td>
                                <td>{{ $contest->participations_allowed_daily }}</td>
                                <td>
                                    <a class="btn btn-success" target="_blank" href="{{ route('admin.contestExport', ['id' => $contest->id]) }}">Export participations</a>
                                </td>
                                <td>
                                    <a class="btn btn-warning" href="{{ route('admin.contestdetail', ['id' => $contest->id]) }}">Edit</a>
                                </td>
                                <td>
                                {!! Form::open(['onsubmit' => 'return ConfirmDelete()', 'method' => 'DELETE','route' => ['contests.destroy', $contest->id],'style'=>'display:inline']) !!}
                                {!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!}
                                {!! Form::close() !!}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $contests->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
