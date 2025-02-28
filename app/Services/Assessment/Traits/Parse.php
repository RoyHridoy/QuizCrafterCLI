<?php

namespace App\Services\Assessment\Traits;

trait Parse
{
    protected function parseJson(string $jsonContent): array
    {
        $start = strpos($jsonContent, '```json') + strlen('```json');
        $end = strpos($jsonContent, '```', $start);
        $jsonResponse = substr($jsonContent, $start, $end - $start);

        return json_decode($jsonResponse, true) ?? [];
    }
}
