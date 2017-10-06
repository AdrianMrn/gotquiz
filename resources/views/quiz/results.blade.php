@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>

                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif
                    <!-- future: show user which questions he got right & wrong -->
                    <!-- future: show attempts left -->

                    <p>Points:</p>
                    <p>{{ $points }} </p>

                    <a href="{{ route('quiz.quiz') }}">Start another quiz!</a>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
