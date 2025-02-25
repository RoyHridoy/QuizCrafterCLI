<?php

namespace App\AI\Contract;

interface AiModel
{
    public function setSystemMessage(string $message): static;

    public function send(string $message): ?string;

    public function reply(string $message): ?string;
}
