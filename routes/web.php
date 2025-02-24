<?php

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

use function Laravel\Prompts\search;

Route::get('/', function () {
    // $response = Http::withToken(config('services.openai.api_key'))->post('https://api.openai.com/v1/chat/completions', [
    //     "model" => "gpt-4o-mini",
    //     "messages" => [
    //         [
    //             "role" => "system",
    //             "content" => "You are an expert tutor to take exam."
    //         ],
    //         [
    //             "role" => "user",
    //             "content" => "Provide 10 mcq question with 4 options and a correct answer where topic is laravel difficulty medium and provide response as question in first array index then 4 questions in 4 array index and answer is another index"
    //         ]
    //     ]
    // ])->json();
    // dd($response['choices'][0]['message']['content']);
    $data = file_get_contents(__DIR__.'/../test.txt');

    $start = strpos($data, '```json') + strlen('```json');
    $end = strpos($data, '```', $start);
    $jsonPart = substr($data, $start, $end - $start);
    $questionSet = json_decode($jsonPart);
    dd($questionSet);
});
