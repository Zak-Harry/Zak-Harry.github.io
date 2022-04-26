<?php

namespace App\Form;

use App\Entity\PlannedWorkDays;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;

class PlannedWorkDaysType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('startshift', DateTimeType::class, [
                'label' => 'Début de journée ',
            ])
            ->add('startlunch', DateTimeType::class, [
                'label' => 'Début de pause repas ',
            ])
            ->add('endlunch', DateTimeType::class, [
                'label' => 'Fin de pause repas ',
            ])
            ->add('endshift', DateTimeType::class, [
                'label' => 'Fin de journée ',
            ])

            ->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $formEvents)
            {
                $form = $formEvents->getForm();
                $user = $formEvents->getData();

                if($user->getId() == null)
                {
                    $form->add('users', EntityType::class, [
                        'class' => User::class,
                        'label' => 'Salarié(es)',
                        'choice_label' => 'getfullname',
                        'multiple' => true,
                        'expanded' => true,
                    ]); 
                }
            })    
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PlannedWorkDays::class,
        ]);
    }
}
