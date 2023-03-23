<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username',TextType::class,[
                'label' => 'Pseudonyme',
                'attr' => [
                    'placeholder' => 'Pseudo'
                ]
            ])
            ->add('first_name',TextType::class,[
                'label' => 'Prénom',
                'attr' => [
                    'placeholder' => 'Prénom'
                ]
            ])
            ->add('last_name',TextType::class,[
                'label' => 'Nom',
                'attr' => [
                    'placeholder' => 'Nom'
                ]
            ])
            ->add('address',TextType::class,[
                'label' => 'Adresse',
                'attr' => [
                    'placeholder' => 'Adresse'
                ]
            ])
            ->add('city',TextType::class,[
                'label' => 'Ville',
                'attr' => [
                    'placeholder' => 'Ville'
                ]
            ])
            ->add('zip_code',TextType::class,[
                'label' => 'Code Postal',
                'attr' => [
                    'placeholder' => 'Code Postal'
                ]
            ])
            ->add('country',TextType::class,[
                'label' => 'Pays',
                'attr' => [
                    'placeholder' => 'Pays'
                ]
            ])
            ->add('email',EmailType::class,[
                'label' => 'Email',
                'attr' => [
                    'placeholder' => 'mail@fyz.fr'
                ]
            ])
            
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer un mot de passe.',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Le mot de passe doit être d\'au-moins {{ limit }} caractères.',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'Veuillez accepter les conditions.',
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
