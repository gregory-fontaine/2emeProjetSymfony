<?php

namespace App\Form;

use App\Entity\Seances;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SeancesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('heure_debut')
            ->add('heure_fin')
            ->add('date')
            ->add('description')
            ->add('libelle')
            ->add('cours_id')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Seances::class,
        ]);
    }
}
