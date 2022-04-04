<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\Note;
use App\Entity\Task;
use App\Entity\User;
use App\FinishTask;
use App\FinishTaskHandler;
use App\Form\NoteType;
use App\Form\TaskType;
use DateTimeImmutable;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class TaskController extends AbstractController
{
    /**
     * @Route("/task/new", name="task_new")
     * @param User $user
     */
    public function new(Request $request,  ManagerRegistry $doctrine, UserInterface $user, HttpClientInterface $client): Response
    {
        $task = new Task();
        $task->setAssignedTo($user);
        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $readabilityScores = $client->request('GET', 'http://readapility.io/api/readability-scores', [
                'body' => $task->getTask(),
            ])->toArray();

            if ($readabilityScores['fleschKincaidReadingEase'] < 70) {
                $form->addError(new FormError('The task description is not easy enough to read'));
            }

            if ($form->isValid()) {
                $task = $form->getData();

                $em = $doctrine->getManager();
                $em->persist($task);
                $em->flush();

                $this->addFlash('success', 'Task added');

                return $this->redirectToRoute('task_list');
            }
        }

        return $this->renderForm('task/new.html.twig', [
            'form' => $form,
        ]);
    }

    /**
     * @Route("/task/edit/{id}", name="task_edit")
     */
    public function edit(Request $request, Task $task, ManagerRegistry $doctrine): Response
    {
        $em = $doctrine->getManager();

        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            return $this->redirectToRoute('task_list');
        }

        return $this->renderForm('task/new.html.twig', [
            'form' => $form,
        ]);
    }

    /**
     * @Route("/task/finish/{id}", name="task_finish", methods={"POST"})
     */
    public function finish(int $id, FinishTaskHandler $handler): Response
    {
        $command = new FinishTask($id);
        $handler->handle($command);

        $this->addFlash('success', 'Task was finished');

        return $this->redirectToRoute('task_show', ['id' => $id]);
    }

    /**
     * @Route("/task/show/{id}", name="task_show")
     */
    public function show(Task $task): Response
    {
        return $this->render('task/show.html.twig', [
            'task' => $task,
        ]);
    }

    /**
     * @Route("/", name="task_list")
     */
    public function list(ManagerRegistry $doctrine): Response
    {
        return $this->render('task/list.html.twig', [
            'tasks' => $doctrine->getRepository(Task::class)->findBy(
                [
                    'isFinished' => false
                ],
                [
                    'dueDate' => 'ASC'
                ]
            ),
            'now' => new DateTimeImmutable()
        ]);
    }

    /**
     * @Route("/task/{id}/add-note", name="task_add_note")
     */
    public function addNote(Request $request, Task $task, ManagerRegistry $doctrine): Response
    {
        $form = $this->createForm(NoteType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $note = $form->getData();

            /** @var Note $note */
            $note->setTask($task);
            $task->getNotes()->add($note);

            $em = $doctrine->getManager();
            $em->flush();

            return $this->redirectToRoute('task_show', ['id' => $task->getId()]);
        }

        return $this->renderForm('task/add-note.html.twig', [
            'form' => $form,
        ]);
    }
}
