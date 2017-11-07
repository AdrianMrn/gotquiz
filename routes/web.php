<?php


Route::get('/', 'HomeController@index')->name('home');
Auth::routes();

Route::get('/redirect', 'SocialAuthFacebookController@redirect');
Route::get('/callback', 'SocialAuthFacebookController@callback');

Route::prefix('quiz')->group(function () {
    Route::get('start', 'QuizController@index')->name('quiz.start');
    Route::get('quiz', 'QuizController@quiz')->name('quiz.quiz');
    Route::post('gradequiz', 'QuizController@gradeQuiz')->name('quiz.gradeQuiz');
});

Route::group(['middleware' => ['auth', 'admin']], function() {
    Route::prefix('admin')->group(function () {
        Route::get('/', ['uses' => 'Admin\DashboardController@index', 'as' => 'admin.dashboard']);
        Route::get('/contests', ['uses' => 'ContestController@getAll', 'as' => 'admin.contests']);
        Route::get('/contests/{id}', ['uses' => 'ContestController@detail', 'as' => 'admin.contestdetail']);
        Route::get('/contests/export/{id}', ['uses' => 'ContestController@exportExcel', 'as' => 'admin.contestExport']);
    });
    
    Route::resource('users','UserController');
    Route::resource('contests','ContestController');
});

//future: use middleware VerifyCsrfToken for forms