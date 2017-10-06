@extends('layouts.app')

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
                <div class="panel-heading">Users</div>
                <div class="panel-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>id</th>
                                <th>Name</th>
                                <th>IP Address</th>
                                <th>Points</th>
                                <th>Address</th>
                                <th>City</th>
                                <th>Role</th>
                                <th>Delete/Disqualify</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->ipaddress }}</td>
                                <td>{{ $user->points }}</td>
                                <td>{{ $user->address }}</td>
                                <td>{{ $user->town }}</td>
                                <td>
                                @if ($user->isAdmin)
                                {!! Form::open(['method' => 'PATCH','route' => ['users.update', $user->id],'style'=>'display:inline']) !!}
                                {!! Form::submit('Demote to user', ['class' => 'btn btn-warning']) !!}
                                {!! Form::close() !!}
                                @else
                                {!! Form::open(['method' => 'PATCH','route' => ['users.update', $user->id],'style'=>'display:inline']) !!}
                                {!! Form::submit('Promote to admin', ['class' => 'btn btn-warning']) !!}
                                {!! Form::close() !!}
                                @endif
                                </td>
                                <td>
                                {!! Form::open(['onsubmit' => 'return ConfirmDelete()', 'method' => 'DELETE','route' => ['users.destroy', $user->id],'style'=>'display:inline']) !!}
                                {!! Form::submit('Delete/Disqualify', ['class' => 'btn btn-danger']) !!}
                                {!! Form::close() !!}
                                
                                </td>
                            </tr>
                            @endforeach
                            
                        </tbody>
                    </table>
                    {{ $users->links() }}
                </div>
            </div>
        </div>

    </div>

    <!-- future: move contests to a different page because pagination doesn't work with both of them here -->
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
                                <th>Started</th>
                                <th>Ends</th>
                                <th>Status</th>
                                <th>Edit</th>
                                <th>Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($contests as $contest)
                            <tr>
                                <td>{{ $contest->id }}</td>
                                <td>{{ $contest->winner_id }}</td>
                                <td>{{ $contest->start }}</td>
                                <td>{{ $contest->end }}</td>
                                <td>{{ $contest->status }}</td>
                                <td>
                                {!! Form::open(['method' => 'GET','route' => ['contests.update', $contest->id],'style'=>'display:inline']) !!}
                                {!! Form::submit('Edit', ['class' => 'btn btn-warning']) !!}
                                {!! Form::close() !!}
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
