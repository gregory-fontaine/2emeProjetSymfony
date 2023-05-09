<?php

namespace App\Form;

use App\Entity\Eleve;
use App\Entity\Seances;
use App\Entity\Presence;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class PresenceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder            
            ->add('validation_presence', ChoiceType::class, [
                'choices' => [
                    'absent' => 0,
                    'présent' => 1,
                    'absence justifiée' => 2
                ],
                'attr'=>[
                    'class'=> 'btn btn-outline-secondary nav-link dropdown-toggle mb-4 mt-1'
                ]
            ])
            ->add('eleve', EntityType::class, [
                'class' => Eleve::class,
                // On utilise le choice label getNomComplet() qui a été créer directement dans l'entité Eleve
                'choice_label' => 'nom_complet',
                'expanded' => true,
                'multiple' => false,
            ])
            //Si on veut modifier la séance
            // ->add('seances', EntityType::class, [
            //     'class' => Seances::class,
            //     'choice_label' => 'id',
            // ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Presence::class,
        ]);
    }
}
