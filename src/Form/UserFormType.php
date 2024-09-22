<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('choice1', TextType::class, [
                'label' => 'Choix 1',
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('choice2', TextType::class, [
                'label' => 'Choix 2',
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('choice3', TextType::class, [
                'label' => 'Choix 3',
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'J\'ai fait mes choix',
                'attr' => [
                    'class' => 'btn btn-danger mt-2 btn-outline-light',
                    'onclick' => 'return confirm("Êtes-vous sûr d\'avoir fait vos choix ? Cela ne pourra plus être modifié car les lutins ont beaucoup de travail cette année !")'],
            ])
        ;

        $builder->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $formEvent) {
            /** @var User $user */
            $user = $formEvent->getData();
            $user->setSubmittedAt(new \DateTimeImmutable());
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
