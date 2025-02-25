<?php

namespace App\AI;

use App\AI\Contract\AiModel;
use Illuminate\Support\Facades\Http;

class ChatGPT implements AiModel
{
    protected array $messages = [];

    public function setSystemMessage(string $message): static
    {
        $this->messages[] = [
            'role' => 'system',
            'content' => $message,
        ];

        return $this;
    }

    public function send(string $message): ?string
    {
        $this->messages[] = [
            'role' => 'user',
            'content' => $message,
        ];

        $response = Http::withToken(config('services.openai.api_key'))->post('https://api.openai.com/v1/chat/completions', [
            'model' => 'gpt-4o-mini',
            'messages' => $this->messages,
        ])->json('choices.0.message.content');

        if (! $response) {
            return null;
        }

        $this->messages[] = [
            'role' => 'assistant',
            'content' => $response,
        ];

        dump($this->messages);

        return $response;
    }

    public function reply(string $message): ?string
    {
        return $this->send($message);
    }
}
