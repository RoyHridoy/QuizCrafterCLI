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

    public function getFeedback(array $questionWithUserAnswer)
    {
        $content = $this->aiModel->send($this->generateFeedback(json_encode($questionWithUserAnswer)));

        return $this->parseJson($content);
    }

    private function generateFeedback(string $questionWithUserAnswer)
    {
        return "Evaluate the user's answers against the following questions, identify and explain only incorrect answers, Provide short explanations to help the user understand the correct answer and Maintain the original question order for clarity:
            Questions including user answer: {$questionWithUserAnswer}
            Output a JSON object:
            {
            \"overall_feedback\": string format but not publish user marks,
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
            }";
    }

    protected function parseJson(string $jsonContent): array
    {
        $start = strpos($jsonContent, '```json') + strlen('```json');
        $end = strpos($jsonContent, '```', $start);
        $jsonResponse = substr($jsonContent, $start, $end - $start);

        return json_decode($jsonResponse, true) ?? [];
    }
}
