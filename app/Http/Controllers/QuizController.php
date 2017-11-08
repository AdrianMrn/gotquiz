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
        $this->amountOfQuestions = env('GOTQUIZ_AMOUNT_OF_QUESTIONS', 5);
        $this->timeAllowed = env('GOTQUIZ_TIMEALLOWED', 120); //amount of seconds allowed to take the quiz
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

        $amountOfQuestions = $this->amountOfQuestions;
        $timeAllowed = $this->timeAllowed;
        
        return view('quiz.start', ['participationsRemaining' => $participationsRemaining, 'currentContest' => $currentContest, 'amountOfQuestions' => $amountOfQuestions, 'timeAllowed' => $timeAllowed]);
    }

    public function gradeQuiz(Request $request) {
        //stopping cheaters & errors
        if (!Auth::check())
        {
            return redirect('quiz/start');
        }
        $quizCompleted = $request->session()->pull('quizcompleted', 1);
        $quizStartTime = $request->session()->pull('quizstarted', Carbon::now()->subHour());
        if ($quizCompleted == 1 || Carbon::now() > $quizStartTime->addSeconds($this->timeAllowed + 15)) { // adding 15 seconds to allow for latency. 15 seconds extra also wouldn't give users an extremely unfair advantage.
            $request->session()->flash('error', 'Something went wrong while grading your attempt so it has been invalidated. Sorry!');
            return redirect('quiz/start');
        }
        $request->session()->put('quizcompleted', 1);

        //referral
        if(Auth::user()->referredBy && !Auth::user()->referralComplete)
        {
            User::find(Auth::user()->referredBy)->increment('extraAttempts');
            Auth::user()->update(['referralComplete' => 1]);
        }
        
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
        if (!$currentContest) {
            $request->session()->flash('error', 'There is currently no contest running.');
            return redirect('quiz/start');
        }
        //checking if the user has any participations left today
        $participationsRemaining = (new ContestController)->participationsRemaining(Auth::user()->id);
        if ($participationsRemaining <= 0)
        {
            $request->session()->flash('error', 'Amount of allowed attempts reached, come back tomorrow!');
            return redirect('quiz/start');
        }

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
        $questionType = rand(0,7); //future: generate random number between 0 & 10
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

                $question->question = $currentLord->name . ' is the lord/lady of which house?';
                $question->answers = $answers;
                $question->correctAnswer = $house->name;
                break;

            //$words are the words of which house?
            case 1:
                $house = House::where([['words', '!=', 'NULL'], ['words', '!=', '']])->orderByRaw("RAND()")->first();
                $words = $house->words;

                $answers = [];
                for ($i = 0; $i<3; $i++) {
                    $dummyHouse = $house;
                    while ($dummyHouse == $house || $dummyHouse->name == $house->name) {
                        $dummyHouse = House::orderByRaw("RAND()")->first();
                    }
                    array_push($answers, $dummyHouse->name);
                }

                array_push($answers, $house->name);
                shuffle($answers);

                $question->question = '"' . $words . '" are the words of which house?';
                $question->answers = $answers;
                $question->correctAnswer = $house->name;
                break;

            //$alias->alias refers to which character?
            case 2:
                $correctName = '';
                while ($correctName == '') {
                    $characterAlias = Alias::orderByRaw("RAND()")->first();
                    $alias = $characterAlias->alias;
                    $correctCharacter = Character::find($characterAlias->character_id);
                    $correctName = $correctCharacter->name;
                }

                $answers = [];
                for ($i = 0; $i<3; $i++) {
                    $dummyCharacterAlias = $characterAlias;
                    $dummyName = '';
                    while ($dummyCharacterAlias == $characterAlias || $dummyCharacterAlias->character_id == $characterAlias->character_id || $dummyName == '') {
                        $dummyCharacterAlias = Alias::orderByRaw("RAND()")->first();
                        $dummyCharacter = Character::find($dummyCharacterAlias->character_id);
                        $dummyName = $dummyCharacter->name;
                    }
                    array_push($answers, $dummyName);
                }

                array_push($answers, $correctCharacter->name);
                shuffle($answers);

                $question->question = '"' . $alias . '" refers to which character?';
                $question->answers = $answers;
                $question->correctAnswer = $correctName;
                break;
            //$house->name resides in which region?
            case 3:
                $correctRegion = '';
                while ($correctRegion == '') {
                    $house = House::where([['region', '!=', 'NULL'], ['region', '!=', '']])->orderByRaw("RAND()")->first();
                    $correctRegion = $house->region;
                }

                $answers = [];

                $regions = House::select('region')->groupBy('region')->get();
                $arrRegions = [];
                foreach ($regions as $region) {
                    if ($region->region) {
                        array_push($arrRegions, $region->region);
                    }
                }

                for ($i = 0; $i<3; $i++) {
                    $dummyRegion = $correctRegion;
                    while ($dummyRegion == $correctRegion) {
                        $rand = rand(0,sizeof($arrRegions)-1);
                        $dummyRegion = $arrRegions[$rand];
                    }
                    array_splice($arrRegions, $rand,1);
                    array_push($answers, $dummyRegion);
                }

                array_push($answers, $correctRegion);
                shuffle($answers);

                $question->question = $house->name . ' resides in which region?';
                $question->answers = $answers;
                $question->correctAnswer = $correctRegion;
                break;
            //$char->name belongs to which culture?
            case 4:
                $character = Character::where([['culture', '!=', 'NULL'], ['culture', '!=', '']])->orderByRaw("RAND()")->first();
                $correctCulture = $character->culture;

                $answers = [];
                
                $cultures = Character::select('culture')->groupBy('culture')->get();
                $arrCultures = [];
                foreach ($cultures as $culture) {
                    if ($culture->culture) {
                        array_push($arrCultures, $culture->culture);
                    }
                }

                for ($i = 0; $i<3; $i++) {
                    $dummyCulture = $correctCulture;
                    while ($dummyCulture == $correctCulture) {
                        $rand = rand(0,sizeof($arrCultures)-1);
                        $dummyCulture = $arrCultures[$rand];
                    }
                    array_splice($arrCultures, $rand,1);
                    array_push($answers, $dummyCulture);
                }

                array_push($answers, $correctCulture);
                shuffle($answers);

                $question->question = $character->name . ' belongs to which culture?';
                $question->answers = $answers;
                $question->correctAnswer = $correctCulture;
                break;
            //Who is $char->name's mother?
            case 5:
                $character = Character::whereNotNull('mother')->orderByRaw("RAND()")->first();
                $correctMother = Character::find($character->mother);

                $answers = [];
                for ($i = 0; $i<3; $i++) {
                    $dummyMother = $correctMother;
                    while ($dummyMother == $correctMother || $dummyMother->name == $correctMother->name) {
                        $dummyMother = Character::orderByRaw("RAND()")->first();
                    }
                    array_push($answers, $dummyMother->name);
                }

                array_push($answers, $correctMother->name);
                shuffle($answers);

                $question->question = 'Who is ' . $character->name . '\'s mother?';
                $question->answers = $answers;
                $question->correctAnswer = $correctMother->name;
                break;
            //Who is $char->name's father?
            case 6:
                $character = Character::whereNotNull('father')->orderByRaw("RAND()")->first();
                $correctFather = Character::find($character->father);

                $answers = [];
                for ($i = 0; $i<3; $i++) {
                    $dummyFather = $correctFather;
                    while ($dummyFather == $correctFather || $dummyFather->name == $correctFather->name) {
                        $dummyFather = Character::orderByRaw("RAND()")->first();
                    }
                    array_push($answers, $dummyFather->name);
                }

                array_push($answers, $correctFather->name);
                shuffle($answers);

                $question->question = 'Who is ' . $character->name . '\'s father?';
                $question->answers = $answers;
                $question->correctAnswer = $correctFather->name;
                break;
            //Who is $char->name's spouse?
            case 7:
                $character = Character::whereNotNull('spouse')->orderByRaw("RAND()")->first();
                $correctSpouse = Character::find($character->spouse);

                $answers = [];
                for ($i = 0; $i<3; $i++) {
                    $dummySpouse = $correctSpouse;
                    while ($dummySpouse == $correctSpouse || $dummySpouse->name == $correctSpouse->name) {
                        $dummySpouse = Character::orderByRaw("RAND()")->first();
                    }
                    array_push($answers, $dummySpouse->name);
                }

                array_push($answers, $correctSpouse->name);
                shuffle($answers);

                $question->question = 'Who is ' . $character->name . '\'s spouse?';
                $question->answers = $answers;
                $question->correctAnswer = $correctSpouse->name;
                break;
            //$char->name is the heir to which house?
            case 8:
                echo "5";
                break;
            //$char->name owes allegiance to which house?
            case 9:
                echo "6";
                break;

        }

        return $question;
    }
}
