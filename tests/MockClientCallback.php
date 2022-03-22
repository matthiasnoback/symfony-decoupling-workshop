<?php
declare(strict_types=1);

namespace App\Tests;

use LogicException;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Contracts\HttpClient\ResponseInterface;

final class MockClientCallback
{
    public const COMPLICATED_TASK_DESCRIPTION = 'An amazingly complicated task description';

    public function __invoke(string $method, string $url, array $options = []): ResponseInterface
    {
        if (!str_ends_with($url, '/api/readability-scores')) {
            throw new LogicException('Unexpected request');
        }

        $scores = [
            'fleschKincaidReadingEase' => 80,
            'smogIndex' => 1
        ];
        if (($options['body'] ?? '') === self::COMPLICATED_TASK_DESCRIPTION) {
            $scores['fleschKincaidReadingEase'] = 50;
        }

        return new MockResponse(json_encode($scores));
    }
}
