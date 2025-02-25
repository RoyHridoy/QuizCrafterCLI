<?php

use App\AI\ChatGPT;
use App\Services\Question\GenerateQuestion;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {

    // $questions = (new GenerateQuestion(new ChatGPT()))->getQuestions();
    $questions = file_get_contents(__DIR__.'/../test.txt');
    dd(json_decode($questions));
});
