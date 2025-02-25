<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\Assessment\TakeInputService;
use App\Services\Question\GenerateQuestion;
use App\AI\ChatGPT;

use function Laravel\Prompts\{alert,info, note, outro, select, text, suggest, spin};

class GiveAssessment extends Command
{
    protected $signature = 'app:assessment';

    protected $description = 'Generate Questions for assessments on specific topic';

    protected GenerateQuestion $generateQuestion;

    protected string $topic = 'laravel';

    protected string $difficultyLevel = 'intermediate';

    protected int $numberOfQuestions = 10;

    public function __construct(
        protected TakeInputService $takeInputService
    ) {

        parent::__construct();

        $this->generateQuestion = new GenerateQuestion(new ChatGPT());
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        [$this->topic, $this->difficultyLevel, $this->numberOfQuestions] = $this->takeInputService->getInputs();

        alert("Your Topic: {$this->topic}, Difficulty: {$this->difficultyLevel}, Total Question: {$this->numberOfQuestions}.");

        $questions = spin(fn () => $this->fetchQuestions(), 'Fetching Questions...');

        /* TODO:
            1) Take Exam and Calculate Result
            2) Show spin to getting feedback
            2) Send Result to AI and get Feedback
            4) Display short feedback
        */
        $totalMarks = 0;
        $userChoices = [];

        foreach ($questions as $question) {
            outro($question->question);
            $userChoice = select(label: 'Choose Correct Option', options: [...$question->options]);

            $userChoices[] = $userChoice;

            if ($userChoice === $question->answer) {
                $totalMarks += 1;
            }
        }
        outro("Your total marks: {$totalMarks}");

        // TODO: Send gpt these answer with total marks and get a short feedback.
        dump($userChoices);
    }

    private function fetchQuestions()
    {
        sleep(2);

        // return $this->generateQuestion
        //     ->setTopic($this->topic)
        //     ->setDifficultyLevel($this->difficultyLevel)
        //     ->setTotalQuestion($this->numberOfQuestions)
        //     ->getQuestions();

        return json_decode(file_get_contents(__DIR__.'/../../../test-full.txt'));
    }
}
