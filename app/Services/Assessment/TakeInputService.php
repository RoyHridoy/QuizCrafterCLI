<?php

namespace App\Services\Assessment;

use function Laravel\Prompts\select;
use function Laravel\Prompts\suggest;
use function Laravel\Prompts\text;

class TakeInputService
{
    protected array $difficultyOptions = ['Beginner', 'Intermediate', 'Advance'];

    public function getInputs(): array
    {
        $topic = text(
            label: 'On which topic you want to give assessment?',
            placeholder: 'Ex. laravel, php, python',
            validate: fn (string $value) => match (true) {
                strlen($value) < 2 => 'The name must be at least 2 characters.',
                strlen($value) > 20 => 'The name must not exceed 20 characters.',
                default => null
            },
            required: true
        );

        $difficulty = select(label: 'Choose difficulty level', options: $this->difficultyOptions);

        $numberOfQuestions = suggest(
            label: 'Number of Questions: ',
            placeholder: 'Ex. 5',
            validate: ['numberOfQuestions' => 'required|numeric|between:3,15'],
            required: true,
            options: [5, 10, 15]
        );

        return [$topic, $difficulty, $numberOfQuestions];
    }
}
