<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\Assessment\InputCollectorService;
use App\Services\Assessment\QuestionCollectorService;
use App\Services\Assessment\ExamEvaluatorService;
use App\AI\ChatGPT;

use function Laravel\Prompts\{alert,info, note, outro, select, text, suggest, spin};

class GiveAssessment extends Command
{
    protected $signature = 'app:assessment';

    protected $description = 'Generate Questions for assessments on specific topic';

    protected QuestionCollectorService $questionCollectorService;

    protected string $topic = 'laravel';

    protected string $difficultyLevel = 'intermediate';

    protected int $numberOfQuestions = 10;

    public function __construct(
        protected InputCollectorService $inputCollectorService,
        protected ExamEvaluatorService $examEvaluatorService
    ) {

        parent::__construct();

        $this->questionCollectorService = new QuestionCollectorService(new ChatGPT());
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        [$this->topic, $this->difficultyLevel, $this->numberOfQuestions] = $this->inputCollectorService->getInputs();

        alert("Your Topic: {$this->topic}, Difficulty: {$this->difficultyLevel}, Total Question: {$this->numberOfQuestions}.");

        $questions = spin(fn () => $this->fetchQuestions(), 'Fetching Questions...');

        [$userChoices, $marks] = $this->examEvaluatorService->evaluate($questions);
        outro("Your marks: {$marks}");

        $feedback = spin(fn () => $this->generateFeedback($userChoices), 'Generating Feedback...');

        /* TODO:
            2) Send Result to AI and get Feedback
            4) Display short feedback
        */
        info($feedback);
    }

    private function fetchQuestions(): array
    {
        return $this->questionCollectorService
            ->setTopic($this->topic)
            ->setDifficultyLevel($this->difficultyLevel)
            ->setTotalQuestion($this->numberOfQuestions)
            ->collectQuestions();
    }

    private function generateFeedback(array $userChoices)
    {
        sleep(2);
        return "Here is your feedback";
    }
}
