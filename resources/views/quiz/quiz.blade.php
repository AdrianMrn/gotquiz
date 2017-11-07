@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">

                <div class="panel-body">
                <h4>GotQuiZ Season {{ $currentContest }}</h4><br/>
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif
                    <!-- future: js timer that SUBMITS the form when it runs out, gets its time from $timeAllowed -->
                    {{ Form::open(array('route' => 'quiz.gradeQuiz')) }}
                    @php
                    $i = 0;
                    @endphp
                        @foreach ($questions as $question)
                            <p>{{ $question->question }}</p>
                            <ul>
                            @foreach ($question->answers as $answer)
                                <p>
                                {{ Form::radio($i, $answer) }}
                                {{ $answer }}
                                </p>
                            @endforeach
                            @php
                                $i++;
                            @endphp
                            </ul>
                        @endforeach
                    <br/>
                    {{ Form::submit('Submit answers') }}
                    {{ Form::close() }}

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
