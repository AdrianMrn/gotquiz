@extends('layouts.app')

@section('content')
<script>
    /* window.onbeforeunload = function() {
        return true;
    }; */
    function startTimer(duration, display) {
    var timer = duration, minutes, seconds;
    setInterval(function () {
        minutes = parseInt(timer / 60, 10)
        seconds = parseInt(timer % 60, 10);

        minutes = minutes < 10 ? "0" + minutes : minutes;
        seconds = seconds < 10 ? "0" + seconds : seconds;

        display.textContent = minutes + ":" + seconds;

        if (--timer <= 0) {
            timer = 0;
            /* window.onbeforeunload = null; */
            document.quizForm.submit();
        }
    }, 1000);
}

window.onload = function () {
    var display = document.querySelector('#time');
    startTimer({{ $timeAllowed }}, display);
};

//stop form submit from asking for confirmation
/* function processForm(e) {
    if (e.preventDefault) e.preventDefault();
    window.onbeforeunload = null;
    return true;
}

var form = document.getElementById('quizForm');
    if (form.attachEvent) {
        form.attachEvent("submit", processForm);
    } else {
        form.addEventListener("submit", processForm);
} */
    
</script>
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
                    <div>Time remaining: <span id="time"></span></div><br/>
                    <!-- future: js timer that SUBMITS the form when it runs out, gets its time from $timeAllowed -->
                    {{ Form::open(array('route' => 'quiz.gradeQuiz', 'name' => 'quizForm', 'id' => 'quizForm')) }}
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
                    {{ Form::submit('Submit answers', ['class' => 'btn btn-primary']) }}
                    {{ Form::close() }}

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
