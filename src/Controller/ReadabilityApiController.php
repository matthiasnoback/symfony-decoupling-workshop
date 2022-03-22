<?php
declare(strict_types=1);

namespace App\Controller;

use DaveChild\TextStatistics\TextStatistics;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api")
 */
final class ReadabilityApiController extends AbstractController
{
    /**
     * @Route("/readability-scores")
     */
    public function index(Request $request): Response
    {
        $text = $request->getContent();
        if (empty($text)) {
            return new JsonResponse(['error' => 'Submit the text as an HTTP request body']);
        }

        $textStatistics = new TextStatistics();

        return new JsonResponse(
            [
                'fleschKincaidReadingEase' => $textStatistics->fleschKincaidReadingEase($text),
                'smogIndex' => $textStatistics->smogIndex($text)
            ]
        );
    }
}
