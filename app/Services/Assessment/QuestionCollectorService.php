<?php

namespace App\Services\Assessment;

use App\AI\Contract\AiModel;

class QuestionCollectorService
{
    protected string $difficultyLevel = 'beginner';

    protected string $topic = 'laravel';

    protected int $numOfQuestions = 10;

    protected string $systemMessage = 'You are an expert tutor for taking exam.';

    public function __construct(protected AiModel $aiModel)
    {
        //
    }

    private function generateQuestions(): string
    {
        return "Generate {$this->numOfQuestions} multiple-choice questions on the topic of {$this->topic} with {$this->difficultyLevel} difficulty. The output must be in the following JSON format for each question and always wrap the array ```json to ```:
        [
            {
                \"index\": question_number,
                \"question\": \"The question text here\",
                \"options\": [\"Option A\", \"Option B\", \"Option C\", \"Option D\"],
                \"answer\": correct_option_index (integer, starting from 0)
            }
        ]";
    }

    public function setDifficultyLevel(string $difficultyLevel): static
    {
        $this->difficultyLevel = $difficultyLevel;

        return $this;
    }

    public function setTopic(string $topic): static
    {
        $this->topic = $topic;

        return $this;
    }

    public function setTotalQuestion(int $numOfQuestions): static
    {
        $this->numOfQuestions = $numOfQuestions;

        return $this;
    }

    public function collectQuestions(): array
    {
        $content = $this->aiModel
            ->setSystemMessage($this->systemMessage)
            ->send($this->generateQuestions());

        return $this->parseJson($content);
    }

    protected function parseJson(string $jsonContent): array
    {
        $start = strpos($jsonContent, '```json') + strlen('```json');
        $end = strpos($jsonContent, '```', $start);
        $jsonResponse = substr($jsonContent, $start, $end - $start);

        return json_decode($jsonResponse, true) ?? [];
    }
}
