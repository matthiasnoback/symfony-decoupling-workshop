<?php
declare(strict_types=1);

namespace App\Form;

use App\Entity\Task;
use App\Entity\User;
use DateTimeImmutable;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class TaskType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('task', TextType::class)
            ->add('dueDate', DateType::class, ['data' => new DateTimeImmutable()])
            ->add('assignedTo', EntityType::class, ['class' => User::class, 'required' => false, 'placeholder' => '(nobody)',])
            ->add('isFinished', CheckboxType::class, ['required' => false])
            ->add('save', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Task::class,
        ]);
    }
}
