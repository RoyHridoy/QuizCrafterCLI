<?php

namespace App\Services\Assessment;

use function Laravel\Prompts\outro;
use function Laravel\Prompts\select;

class ExamEvaluatorService
{
    protected array $userChoices = [];

    public function evaluate(array $questions): array
    {
        $totalMarks = 0;

        foreach ($questions as $question) {
            // dump($question);
            outro($question['question']);
            $userChoice = select(label: 'Choose Correct Option', options: [...$question['options']]);

            $this->userChoices[] = $userChoice;

            if ($userChoice === $question['options'][$question['answer']]) {
                $totalMarks += 1;
            }
        }
        return [
            $this->userChoices,
            $totalMarks,
        ];
    }
}
