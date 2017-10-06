<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/', 'HomeController@index')->name('home');

Route::prefix('quiz')->group(function () {
    Route::get('start', 'QuizController@index')->name('quiz.start');
    Route::get('quiz', 'QuizController@quiz')->name('quiz.quiz');
    Route::post('gradequiz', 'QuizController@gradeQuiz')->name('quiz.gradeQuiz');
});

Route::group(['middleware' => ['auth', 'admin']], function() {
    Route::prefix('admin')->group(function () {
        Route::get('/', ['uses' => 'Admin\DashboardController@index', 'as' => 'admin.dashboard']);
    });
    
    Route::get('/contests/{id}', 'ContestController@detail');

    Route::resource('users','UserController');
    Route::resource('contests','ContestController');
});

//future: use middleware VerifyCsrfToken for forms