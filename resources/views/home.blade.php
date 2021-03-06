@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8">
            @if(Session::has('error'))
                <p class="alert alert-danger">{{ Session::get('error') }}</p>
            @endif
            <h3>Game of Thrones Quiz</h3>
            <p>
                GoTQuiz, or Game of Thrones Quiz, is a contest for fans of the series. 
                There's a number of prizes you can win (books, bobbleheads, exclusive props from the set, ...) by competing.
            </p>
            <p>
                You join the contest by creating an account and completing the quiz at least once. You get a couple of attempts per contest, 
                and your score will stack! Each attempt will refresh after 24 hours, so you have a bigger chance at winning the best prizes if you come back every day! 
            </p>
            <p>
                Questions in the quiz are randomly generated from our database, there's an estimated 10.000+ possible questions!
            </p>
            <a href="{{ route('quiz.start') }}" class="btn btn-default">Go to the quiz</a>
        </div>

        <div class="col-md-4">
            <h3>Past seasons</h3>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th></th>
                        <th>Winner</th>
                        <th>Points</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($contests as $contest)
                    <tr>
                        <td><strong>{{ $contest->id }}</strong></td>
                        <td>{{ $contest->winnername }}</td>
                        <td>{{ $contest->winner_points }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @if (Auth::check())
    <div class="row top-buffer">
        <div class="col-md-4">
            <h3>Referrals</h3>
            <p>
                You can invite friends to GoTQuiz using the referral link below. 
                Every invited friend that plays at least one game will gain you a permanent 
                one extra chance per day in every contest.
            </p>
            <p><input type="text" class="form-control" id="usr" readonly="readonly" value="{{ route('referral', ['id'=>Auth::user()->id]) }}"></p>
        </div>
    </div>
    @endif
</div>
@endsection
