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

class UtilisateurAdminType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            //mail
            ->add('email', EmailType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'minlenght' => '2',
                    'maxlenght' => '180'
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
            

            //sexe de l'utilisateur            
            ->add('sexe_utilisateur', ChoiceType::class, [
                'choices' => [
                    'homme' => "homme",
                    'femme' => 'femme'
                ],
                'attr' => [
                    'class' => 'btn btn-outline-primary dropdown-toggle mx-auto d-flex justify-content-center '                    
                ],
                'label_attr' => [
                    'class' => 'form-label d-flex justify-content-center fs-4'
                ]    
            ])


             // Choix des roles
             ->add('roles', ChoiceType::class, [
                'choices' => [
                    'Administrateur' => "ROLE_ADMIN",
                    'Utilisateur  ' => 'ROLE_USER',
                ],
                'label_attr' => [
                    'class' => 'form-label d-flex justify-content-center fs-4'
                ],
                'attr' => [
                    'class' => ' mx-4 px-3 d-flex justify-content-around fs-5'                    
                ],
                'multiple' => true,
                'expanded' => true,
                'label' => 'Rôles',
                'choice_value' => function ($value) {
                    return $value;
                },
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
