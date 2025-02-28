<?php

namespace App\Services\Assessment;

use function Laravel\Prompts\info;
use function Laravel\Prompts\note;
use function Laravel\Prompts\outro;
use function Laravel\Prompts\table;
use function Laravel\Prompts\warning;

class DisplayFeedbackService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function display(array $feedback)
    {
        outro($feedback['overall_feedback']);

        foreach ($feedback['feedback'] as $_feedback) {
            outro("Question: # {$_feedback['index']}: {$_feedback['question']}");
            info("Your answer: {$_feedback['user_answer']}");
            info("Correct answer: {$_feedback['correct_answer']}");
            note("Explanation: {$_feedback['explanation']}");
        }
    }
}
