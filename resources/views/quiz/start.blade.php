@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">GoTQuiz</div>

                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if (Auth::check())
                        @if(Session::has('points'))
                            <p class="alert alert-info">Points in last attempt: {{ Session::get('points') }}</p>
                        @endif
    
                        <p>description goes here</p> <!-- future: short tutorial/description of the quiz -->

                        <p>Participations remaining today: {{ $participationsRemaining }}</p>
    
                        <a href="{{ route('quiz.quiz') }}" class="btn btn-primary {{ $participationsRemaining <= 0 ? "disabled" : "" }}">Start the quiz!</a>
    
                        @if(Session::has('error'))
                            <p class="alert alert-danger">{{ Session::get('error') }}</p>
                        @endif
                    @else
                        <p>You have to be logged in to an account to participate!</p>

                    @endif


                </div>
            </div>
        </div>
    </div>
</div>
@endsection
