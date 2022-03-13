<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class TaskController extends AbstractController
{
    /**
     * @Route("/task/new", name="task_new")
     */
    public function new(Request $request, ManagerRegistry $doctrine): Response
    {
        $form = $this->createForm(TaskType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $task = $form->getData();

            $em = $doctrine->getManager();
            $em->persist($task);
            $em->flush();

            return $this->redirectToRoute('task_list');
        }

        return $this->renderForm('task/new.html.twig', [
            'form' => $form,
        ]);
    }

    /**
     * @Route("/task/edit/{id}", name="task_edit")
     */
    public function edit(Request $request, ManagerRegistry $doctrine): Response
    {
        $em = $doctrine->getManager();

        $task = $em->find(Task::class, $request->attributes->getInt('id'));

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
     * @Route("/", name="task_list")
     */
    public function list(ManagerRegistry $doctrine): Response
    {
        return $this->render('task/list.html.twig', [
            'tasks' => $doctrine->getRepository(Task::class)->findAll()
        ]);
    }
}
