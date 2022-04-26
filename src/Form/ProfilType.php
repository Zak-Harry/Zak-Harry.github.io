<?php

namespace App\Form;



use App\Entity\Departement;
use App\Entity\Job;
use App\Entity\Role;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Constraints\NotBlank;

class ProfilType extends AbstractType
{
    private Security $security;

    private bool $is_granted = true;
    public function __construct(Security $security)
    {
        $this->security =  $security;
        $user = $this->security->getUser()->getRoles()[0];

        if ($user === "ROLE_RH")
        {
            $this->is_granted = false;
        }
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('lastname', TextType::class, [
                'label' => 'Nom du salarié',
                'trim' => true,
                'required' => true,
                'disabled' => $this->is_granted
            ])
            ->add('firstname', TextType::class,
                [
                'disabled' => $this->is_granted,
                'label' => 'Prénom du salarié',
                'trim' => true,
                'required' => true
            ])
            ->add('dateOfBirth', BirthdayType::class, [
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                'disabled' => $this->is_granted,
            ])
            /**
            ->add('picture', UrlType::class, [
                'disabled' => $this->is_granted,
                'label' => 'Photo du salarié',
                'required' => false
            ])
            */
            ->add('email', EmailType::class, [
                'label' => 'Email personnel du salarié',
                'required' => true
            ])

            ->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $formEvents)
            {
                $form = $formEvents->getForm();
                $user = $formEvents->getData();

                if($user->getId() !== null)
                {
                    $form->add('password', PasswordType::class, [
                        'mapped' => false,
                        'attr' => [
                            'placeholder' => 'Laissez vide si inchangé'
                        ]
                ])
                        ->add('emailpro', EmailType::class, [
                            'disabled' => $this->is_granted,
                            'label' => 'Email professionnelle du salarié',
                            'required' => true
                        ]);
                }
            })
            ->add('phonenumber', TextType::class, [
                'label' => 'Numéro de téléphone personnel',
                'required' => true
            ])
            ->add('phonenumberpro', TextType::class, [
                'disabled' => $this->is_granted,
                'label' => 'Numéro de téléphone professionnel',
                'required' => true
            ])
            ->add('address', TextareaType::class, [
                'label' => 'Adresse du salarié',
                'required' => true
            ])
            ->add('city',  TextType::class, [
                'label' => 'Votre ville',
                'required' => true
            ])
            ->add('zipcode',  TextType::class, [
                'label' => 'Code postal',
                'required' => true
            ])
            ->add('rib',  TextType::class, [
                'label' => 'Votre Relevé d\'identité bancaire',
                'required' => true
            ])
            ->add('status', ChoiceType::class, [
                'disabled' => $this->is_granted,
                'choices' => [
                    'Inactif' => 0,
                    'Actif' => 1
                ]
            ])
            ->add('role', EntityType::class, [
                'class' => Role::class,
                'disabled' => $this->is_granted,
                'multiple' => false,
                'expanded' => false,
                ]
            )
            ->add('job', EntityType::class, [
                'class' => Job::class,
                'disabled' => $this->is_granted,
                'choice_label' => 'name',
                'multiple' => false,
                'expanded' => false,
            ])
            ->add('departement', EntityType::class, [
                'class' => Departement::class,
                'choice_label' => 'name',
                'disabled' => $this->is_granted,
                'multiple' => false,
                'expanded' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'attr' => ['novalidate' => 'novalidate']
        ]);
    }
}
