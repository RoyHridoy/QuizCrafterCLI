<?php

namespace App\Console\Commands;

use App\AI\ChatGPT;
use App\Services\Assessment\DisplayFeedbackService;
use App\Services\Assessment\ExamEvaluatorService;
use App\Services\Assessment\FeedbackCollectorService;
use App\Services\Assessment\InputCollectorService;
use App\Services\Assessment\QuestionCollectorService;
use Illuminate\Console\Command;

use function Laravel\Prompts\alert;
use function Laravel\Prompts\outro;
use function Laravel\Prompts\spin;

class GiveAssessment extends Command
{
    protected $signature = 'quiz:start';

    protected $description = 'Generate Questions for assessments on specific topic';

    protected QuestionCollectorService $questionCollectorService;

    protected FeedbackCollectorService $feedbackCollectorService;

    protected string $topic = 'laravel';

    protected string $difficultyLevel = 'intermediate';

    protected int $numberOfQuestions = 10;

    public function __construct(
        protected InputCollectorService $inputCollectorService,
        protected ExamEvaluatorService $examEvaluatorService,
        protected DisplayFeedbackService $displayFeedbackService
    ) {

        parent::__construct();

        $aiModel = new ChatGPT;
        $this->questionCollectorService = new QuestionCollectorService($aiModel);
        $this->feedbackCollectorService = new FeedbackCollectorService($aiModel);
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        [$this->topic, $this->difficultyLevel, $this->numberOfQuestions] = $this->inputCollectorService->getInputs();

        // Fetching Questions
        alert("Your Topic: {$this->topic}, Difficulty: {$this->difficultyLevel}, Total Question: {$this->numberOfQuestions}.");
        $questions = spin(fn () => $this->fetchQuestions(), 'Fetching Questions...');

        // Evaluate Exam
        [$userChoices, $marks, $numberInPercent] = $this->examEvaluatorService->evaluate($questions);
        outro("Your marks: {$marks} ({$numberInPercent}%)");

        // Prepare data for getting feedback
        $questionWithUserAnswer = collect($questions)
            ->map(function ($question, $index) use ($userChoices) {
                $question['user_answer'] = $userChoices[$index];

                return $question;
            })->toArray();

        // Get Feedbacks and show them
        $feedback = spin(fn () => $this->generateFeedback($questionWithUserAnswer), 'Generating Feedback...');

        $this->displayFeedbackService->display($feedback);
    }

    private function fetchQuestions(): array
    {
        return $this->questionCollectorService
            ->setTopic($this->topic)
            ->setDifficultyLevel($this->difficultyLevel)
            ->setTotalQuestion($this->numberOfQuestions)
            ->collectQuestions();
    }

    private function generateFeedback(array $questionWithUserAnswer)
    {
        return $this->feedbackCollectorService
            ->getFeedback($questionWithUserAnswer);
    }
}
