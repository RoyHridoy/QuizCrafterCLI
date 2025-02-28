<?php

namespace App\Services\Assessment;

use App\AI\Contract\AiModel;

class FeedbackCollectorService
{
    /**
     * Create a new class instance.
     */
    public function __construct(protected AiModel $aiModel)
    {
        //
    }

    public function getFeedback(array $questions, array $userChoices)
    {
        $content = $this->aiModel->send($this->generateFeedback(json_encode($questions), json_encode($userChoices)));

        return $this->parseJson($content);
    }

    private function generateFeedback(string $questionsInJson, string $answersInJson)
    {
        return "Evaluate the user's answers against the following questions:
            Questions: {$questionsInJson}
            User's Answers:  {$answersInJson}
            Output a JSON object:
            {
            \"total_questions\": number,
            \"correct_answers\": number,
            \"incorrect_answers\": number,
            \"overall_feedback\": string,
            \"feedback\": [
                {
                \"index\": question_number,
                \"question\": \"Original question\",
                \"user_answer\": \"User's selected option\",
                \"correct_answer\": \"Correct option\",
                \"explanation\": \"Why the correct answer is right\"
                },
                ...
            ]
            }

            Instructions:
            1. Identify and explain only incorrect answers.
            2. Provide short explanations to help the user understand the correct answer.
            3. Maintain the original question order for clarity.
            ";
    }

    protected function parseJson(string $jsonContent): array
    {
        $start = strpos($jsonContent, '```json') + strlen('```json');
        $end = strpos($jsonContent, '```', $start);
        $jsonResponse = substr($jsonContent, $start, $end - $start);

        return json_decode($jsonResponse, true) ?? [];
    }
}
