<?php

namespace App\Form;

use App\Entity\Utilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class Utilisateur1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'minlenght' => '2',
                    'maxlenght' => '180'
                ],
                'label_attr' =>[
                    'class' => 'form-label'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'entrez votre mail'
                    ]),
                    new Email(),
                    new Length([
                    'min' => 2,
                    'max' => 180,
                    'minMessage'=>"votre mail est trop court",
                    'maxMessage' => "votre mail est trop long"])
                ]
            ])
           
            ->add('password', RepeatedType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'type' => PasswordType::class,                
                'mapped' => false,
                'first_options' => [
                    'label' => 'Nouveau Mot de passe',
                    'attr' => [
                        'autocomplete' => 'new-password',
                        'class' => 'form-control',
                        'minlenght' => '6',
                        'maxlenght' => '4096'
                    ],
                    'label_attr' => [
                        'class' => 'form-label'
                    ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Entrez un mot de passe',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ],
                'second_options' => [
                    'label' => 'Confirmation du nouveau mot de passe',
                    'attr' => [
                        'class' => 'form-control',
                        'minlenght' => '6',
                        'maxlenght' => '4096'
                    ],
                    'label_attr' => [
                        'class' => 'form-label'
                    ]
                ],
                'invalid_message' => 'Les mots de passe ne correspondent pas.'
            ])
            ->add('nom_utilisateur', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'minlenght' => '2',
                    'maxlenght' => '255'
                ],                
                'label_attr' =>[
                    'class' => 'form-label'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'entrez votre nom'
                    ]),
                    new Length([
                    'min' => 2,
                    'max' => 255,
                    'minMessage'=>"votre nom est trop court",
                    'maxMessage' => "votre nom est trop long"])
                ]
            ])
            ->add('prenom_utilisateur', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'minlenght' => '2',
                    'maxlenght' => '255'
                ],                
                'label_attr' =>[
                    'class' => 'form-label'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'entrez votre prénom'
                    ]),
                    new Length([
                    'min' => 2,
                    'max' => 255,
                    'minMessage'=>"votre prénom est trop court",
                    'maxMessage' => "votre prénom est trop long"])
                ]
            ])
            ->add('telephone_utilisateur', TelType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => '+33642595959'                    
                ],
                'label_attr' => [
                    'class' => 'form-label'
                ],            
                'constraints' => [
                    new NotBlank([
                        'message' => 'entrez votre numéro de téléphone',
                    ]),
                    new Length([
                        'min' => 11,                        
                        'max' => 12,
                        'minMessage'=>"entrez un numéro de téléphone valide",
                        'maxMessage'=>"entrez un numéro de téléphone valide"
                    ]),
                    new Type([
                        'type' => 'numeric',
                        'message' => "entrez un numéro de téléphone valide"
                    ])
                ],
            ])
   
            ->add('sexe_utilisateur', ChoiceType::class, [
                'choices' => [
                    'homme' => "homme",
                    'femme' => 'femme'
                ],
                'attr' => [
                    'class' => 'btn btn-primary btn-sm dropdown-toggle '                    
                ],
                'label_attr' => [
                    'class' => 'form-label '
                ]    
            ])
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
        ]);
    }
}
