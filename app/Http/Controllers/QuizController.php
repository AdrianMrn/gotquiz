<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Carbon\Carbon;

use App\Http\Controllers\ContestController;

use App\Alias, App\Character, App\House, App\Seat, App\Title;
use App\Participation, App\User;

class QuizController extends Controller
{
    function __construct() {
        $this->amountOfQuestions = 3;
        $this->timeAllowed = 60; //amount of seconds allowed to take the quiz
    } 

    public function index()
    {
        $participationsRemaining = 0;
        $currentContest = 0;
        $currentContest = (new ContestController)->currentContest();
        if ($currentContest)
        {
            if (Auth::check())
            {
                $participationsRemaining = (new ContestController)->participationsRemaining(Auth::user()->id);
            }
        }
        
        return view('quiz.start', ['participationsRemaining' => $participationsRemaining, 'currentContest' => $currentContest]);
    }

    public function gradeQuiz(Request $request) {
        //stopping cheaters & errors
        if (!Auth::check())
        {
            return redirect('quiz/start');
        }
        $quizCompleted = $request->session()->get('quizcompleted', 1);
        $quizStartTime = $request->session()->get('quizstarted', Carbon::now()->subDay());
        if ($quizCompleted == 1 || Carbon::now() > $quizStartTime->addSeconds($this->timeAllowed + 5)) { // adding 5 seconds to allow for latency. 5 seconds extra also wouldn't give users a very unfair advantage.
            $request->session()->flash('error', 'Something went wrong when grading your attempt so it has been invalidated. Sorry!'); 
            return redirect('quiz/start');
        }
        $request->session()->put('quizcompleted', 1);
        
        $points = 0;

        $correctAnswers = $request->session()->pull('answers');
        $userAnswers = $request->all();

        for ($i=0; $i<$this->amountOfQuestions; $i++) {
            if (array_key_exists($i, $userAnswers) && $correctAnswers[$i] == $userAnswers[$i]) {
                $points++;
            }
        }

        $participation = Participation::find($request->session()->pull('participationid'));
        $participation->points = $points;
        $participation->save();

        $request->session()->flash('points', $points); 
        return redirect('quiz/start');
    } 

    public function quiz(Request $request) {
        if (!Auth::check())
        {
            return redirect('quiz/start');
        }
        $currentContest = (new ContestController)->currentContest();
        //checking if the user has any participations left today
        $participationsRemaining = (new ContestController)->participationsRemaining(Auth::user()->id);
        if ($participationsRemaining <= 0)
        {
            $request->session()->flash('error', 'Amount of allowed attempts reached, come back tomorrow!'); 
            return redirect('quiz/start');
        }

        $currentContest = (new ContestController)->currentContest();
        $participation = new Participation;
        $participation->points = 0;
        $participation->user_id = Auth::user()->id;
        $participation->contest_id = $currentContest;
        $participation->save();
        $request->session()->put('participationid', $participation->id); //saving the participation id in session for when grading
        
        $questions = [];
        $correctAnswers = [];
        //format of a question: {question: "", answers: [], correctAnswer: ""}
        for ($i = 0; $i < $this->amountOfQuestions; $i++) {
            $question = $this->createQuestion();
            array_push($correctAnswers, $question->correctAnswer);
            array_push($questions, $question);
        }

        //creating an answers key in session to save everything needed to grade the quiz (correct answers in original question order)
        $quizInfo = new \stdClass();
        $request->session()->put('answers', $correctAnswers);

        //stopping cheaters
        $request->session()->put('quizcompleted', 0); //submitting the quiz form more than once
        $request->session()->put('quizstarted', Carbon::now()); //circumventing/disabling the js timer to give them more time

        return view('quiz.quiz', ['questions' => $questions, 'timeAllowed' => $this->timeAllowed, 'currentContest' => $currentContest]);
    }

    protected function createQuestion()
    {
        $questionType = 0; //future: generate random number between 0 & 10
        $question = new \stdClass();
        switch ($questionType) {
            //$currentLord is the current lord/lady of which house?
            case 0:
                $house = House::whereNotNull('currentLord')->orderByRaw("RAND()")->first();
                $currentLord = Character::find($house->currentLord);

                $answers = [];
                for ($i = 0; $i<3; $i++) {
                    $dummyHouse = $house;
                    while ($dummyHouse == $house || $dummyHouse->currentLord == $house->currentLord) {
                        $dummyHouse = House::orderByRaw("RAND()")->first();
                    }
                    array_push($answers, $dummyHouse->name);
                }

                array_push($answers, $house->name);
                shuffle($answers);

                $question->question = $currentLord->name . " is the lord/lady of which house?";
                $question->answers = $answers;
                $question->correctAnswer = $house->name;
                break;

            //$words are the words of which house?
            case 1:
                echo "1";
                break;

            //$alias->alias refers to which character?
            case 2:
                echo "2";
                break;
            //$house->name resides in which region?
            case 3:
                echo "3";
                break;
            //$char->name belongs to which culture?
            case 4:
                echo "4";
                break;
            //$char->name is the heir to which house?
            case 5:
                echo "5";
                break;
            //$char->name owes allegiance to which house?
            case 6:
                echo "6";
                break;
            //Who is $char->name's mother?
            case 7:
                echo "7";
                break;
            //Who is $char->name's father?
            case 8:
                echo "8";
                break;
            //Who is $char->name's spouse?
            case 9:
                echo "9";
                break;
            //$house->coatOfArms describe's which house's coat of arms?
            case 10:
                echo "10";
                break;

        }

        return $question;
    }
}
