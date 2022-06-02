<?php
declare(strict_types=1);

namespace App\Controller;

use App\Tasks\Domain\Model\Task\Task;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/api/task")
 */
final class TaskApiController extends AbstractController
{
    /**
     * @Route("/new", name="api_task_new", methods={"POST"})
     */
    public function new(Request $request, SerializerInterface $serializer, ManagerRegistry $doctrine): Response
    {
        $task = $serializer->deserialize($request->getContent(), Task::class, 'json');

        $em = $doctrine->getManager();
        $em->persist($task);
        $em->flush();

        return new Response('', Response::HTTP_CREATED);
    }

    /**
     * @Route("/edit/{id}", name="api_task_edit", methods={"POST"})
     */
    public function edit(Request $request, SerializerInterface $serializer, ManagerRegistry $doctrine): Response
    {
        $em = $doctrine->getManager();

        $task = $em->find(Task::class, $request->attributes->getInt('id'));

        $serializer->deserialize($request->getContent(), Task::class, 'json', [
            AbstractNormalizer::OBJECT_TO_POPULATE => $task
        ]);

        $em->flush();

        return new Response('', Response::HTTP_OK);
    }

    /**
     * @Route("/list", name="api_task_list")
     */
    public function list(ManagerRegistry $doctrine, SerializerInterface $serializer): Response
    {
        return new Response($serializer->serialize(
            $doctrine->getRepository(Task::class)->findAll(),
            'json',
            [
                DateTimeNormalizer::FORMAT_KEY => 'Y-m-d',
            ]
        ), 200, [
            'content-type' => 'application/json'
        ]);
    }
}
