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
                    <!-- future: check if logged in first, don't show start button if not logged in {{ Auth::user() }} -->
                    <!-- future: short tutorial/description of the quiz -->
                    <!-- future: show amount of attempts left for today, and hide/disable the start button if >=5 -->

                    <p>description goes here</p>

                    <a href="{{ route('quiz.quiz') }}">Start the quiz!</a>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
