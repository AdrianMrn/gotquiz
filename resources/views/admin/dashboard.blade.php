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
                                @if ($user->id != 1)
                                    @if ($user->isAdmin)
                                    {!! Form::open(['method' => 'PATCH','route' => ['users.update', $user->id],'style'=>'display:inline']) !!}
                                    {!! Form::submit('Demote to user', ['class' => 'btn btn-warning']) !!}
                                    {!! Form::close() !!}
                                    @else
                                    {!! Form::open(['method' => 'PATCH','route' => ['users.update', $user->id],'style'=>'display:inline']) !!}
                                    {!! Form::submit('Promote to admin', ['class' => 'btn btn-warning']) !!}
                                    {!! Form::close() !!}
                                    @endif
                                @endif
                                </td>
                                <td>
                                @if ($user->id != 1)
                                {!! Form::open(['onsubmit' => 'return ConfirmDelete()', 'method' => 'DELETE','route' => ['users.destroy', $user->id],'style'=>'display:inline']) !!}
                                {!! Form::submit('Delete/Disqualify', ['class' => 'btn btn-danger']) !!}
                                {!! Form::close() !!}
                                @endif
                                
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
</div>
@endsection
