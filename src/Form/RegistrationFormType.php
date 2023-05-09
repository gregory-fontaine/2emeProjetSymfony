<?php

namespace App\Form;

use App\Entity\Utilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Positive;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;


/**
 * Classe du formulaire d'inscription (lié à templates/registration/register.hhtml.twig)
 */
class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            //mail
            ->add('email', EmailType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'minlenght' => '2',
                    'maxlenght' => '180',
                ],
                'label_attr' =>[
                    'class' => 'form-label d-flex justify-content-center fs-4'
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
            
            
            //conditions d'utilisations
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'label' => "J'accepte les conditions génrérales d'utilisations      ",
                'label_attr' => [
                    'class' => 'form-check-label fs-5 mx-3'
                ],
                'attr' => [
                    'class' => 'form-check-label'
                ],
                'constraints' => [
                    new IsTrue([
                        'message' => "Vous avez accepté les conditions d'utilisations",
                    ]),
                ],
            ])
            //mot de passe avec deux champs d'entrés
            ->add('plainPassword', RepeatedType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'type' => PasswordType::class,                
                'mapped' => false,
                'first_options' => [
                    'label' => 'Mot de passe',
                    'attr' => [
                        'autocomplete' => 'new-password',
                        'class' => 'form-control',
                        'minlenght' => '6',
                        'maxlenght' => '4096'
                    ],
                    'label_attr' => [
                        'class' => 'form-label d-flex justify-content-center fs-4'
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
                    'label' => 'Confirmation du mot de passe',
                    'attr' => [
                        'class' => 'form-control',
                        'minlenght' => '6',
                        'maxlenght' => '4096'
                    ],
                    'label_attr' => [
                        'class' => 'form-label d-flex justify-content-center fs-4'
                    ]
                ],
                'invalid_message' => 'Les mots de passe ne correspondent pas.'
            ])               
            //nom de l'utilisateur         
            ->add('nom_utilisateur', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'minlenght' => '2',
                    'maxlenght' => '255'
                ],                
                'label_attr' =>[
                    'class' => 'form-label d-flex justify-content-center fs-4'
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
            //prénom de l'utilisateur
            ->add('prenom_utilisateur', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'minlenght' => '2',
                    'maxlenght' => '255'
                ],                
                'label_attr' =>[
                    'class' => 'form-label d-flex justify-content-center fs-4'
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
            //téléphone
            ->add('telephone_utilisateur', TelType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => '+33642595959'                    
                ],
                'label_attr' => [
                    'class' => 'form-label d-flex justify-content-center fs-4'
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

            ->add('color', ColorType::class, [
                'label' => 'Choisir une couleur',
            ])
          
            //sexe de l'utilisateur            
            ->add('sexe_utilisateur', ChoiceType::class, [
                'choices' => [
                    'homme' => "homme",
                    'femme' => 'femme'
                ],
                'attr' => [
                    'class' => 'btn btn-primary btn-sm dropdown-toggle mx-auto d-flex justify-content-center'                    
                ],
                'label_attr' => [
                    'class' => 'form-label  d-flex justify-content-center fs-4'
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
