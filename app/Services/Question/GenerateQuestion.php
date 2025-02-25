<?php

namespace App\Services\Question;

use App\AI\Contract\AiModel;

class GenerateQuestion
{
    protected string $difficultyLevel = 'beginner';

    protected string $topic = 'laravel';

    protected int $numOfQuestions = 10;

    protected string $systemMessage = 'You are an expert tutor to take exam.';

    protected string $questionPrompt = 'Provide 10 mcq question with 4 options and a correct answer where topic is laravel difficulty medium and provide response as question in first array index then 4 questions in 4 array index and answer is another index';

    public function __construct(protected AiModel $aiModel)
    {
        //
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

    public function getQuestions(): array
    {
        $content = $this->aiModel
            ->setSystemMessage($this->systemMessage)
            ->send($this->questionPrompt);

        return $this->parseJson($content);
    }

    protected function parseJson(string $jsonContent): array
    {
        $start = strpos($jsonContent, '```json') + strlen('```json');
        $end = strpos($jsonContent, '```', $start);
        $jsonResponse = substr($jsonContent, $start, $end - $start);

        return json_decode($jsonResponse);
    }
}
