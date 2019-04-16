<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With, enctype');
header('Access-Control-Allow-Methods: GET, PATCH, POST, DELETE');

Route::group(['middleware' => ['jwt.verify']], function() {
    Route::get('/user', 'UserController@getAuthenticatedUser');
    Route::get('/', 'UserController@index');
    Route::post('/changepassword/{id}', 'UserController@updatePassword');
    Route::get('/myrole', 'UserController@getRole');
    Route::post('/user', 'UserController@store');
    Route::patch('/user/{id}', 'UserController@update');
    Route::delete('/user/{id}', 'UserController@destroy');

    Route::get('/allcase', 'CaseCompetitionController@index');
    Route::get('/dashboard', 'CaseCompetitionController@dashboard');
    Route::get('/caseCompetition/{id}', 'CaseCompetitionController@updateStatus');
    Route::post('/caseCompetition', 'CaseCompetitionController@store');
    Route::patch('/caseCompetition/{id}', 'CaseCompetitionController@update');
    Route::delete('/caseCompetition/{id}', 'CaseCompetitionController@destroy');
    
    Route::get('/quiz', 'QuizCompetitionController@index');
    Route::post('/quiz', 'QuizCompetitionController@store');
    Route::patch('/quiz/{id}', 'QuizCompetitionController@update');
    Route::delete('/quiz/{id}', 'QuizCompetitionController@destroy');


    Route::get('/option', 'OptionsQuizController@index');
    Route::get('/option/{id}', 'OptionsQuizController@show');
    Route::post('/option', 'OptionsQuizController@store');
    Route::patch('/option/{id}', 'OptionsQuizController@update');
    Route::delete('/option/{id}', 'OptionsQuizController@destroy');

    Route::get('/startquiz', 'UserResultController@startquiz');
    Route::get('/showquiz', 'UserResultController@show');
    Route::get('/showquiz/{id}', 'UserResultController@showQuiz');
    Route::post('/answer', 'UserResultController@store');
    Route::get('/alluser', 'UserResultController@index');

    Route::get('/kasus','CaseCompetitionController@index');
    Route::get('/onkasus','CaseCompetitionController@onkasus');
    Route::get('/kasus/{id}','CaseCompetitionController@show');
    Route::post('/kasus','CaseCompetitionController@store');
    Route::post('/kasus/{id}','CaseCompetitionController@update');
    Route::post('/kasus/status/{id}','CaseCompetitionController@updateStatus');
    Route::delete('/kasus/{id}','CaseCompetitionController@destroy');

});
Route::get('/username/{username}', 'UserController@username');

Route::group([
    'prefix' => 'auth'
], function ($router) {
    Route::post('/login', 'UserController@login');
    Route::post('/register', 'UserController@register');
});