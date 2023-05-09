<?php

namespace App\Form;

use App\Entity\Eleve;
use App\Entity\Inscrit;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InscritType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('eleve', EntityType::class, [
                'class' => Eleve::class,
                // 'choice_label' => 'nom_eleve',
                // On utilise le choice label getNomComplet() qui a été créer directement dans l'entité Eleve
                'choice_label' => 'nom_complet',               
                'expanded' => true,
                'multiple' => false, 
                'label' => 'élèves',
                'label_attr' => [
                    'class' => 'form-label d-flex fs-4 '
                ],
                'attr' => [
                    'class' => 'form-check'
                ]               
            ])
       
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Inscrit::class,
        ]);
    }
}
