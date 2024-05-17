<?php

namespace App\Form;

use App\Entity\Bureau;
use App\Entity\Personne;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class PersonneType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('prenom')
            ->add('numero_tel')
            ->add('date_arrive', null, [
                'widget' => 'single_text',
            ])
            ->add('date_depart', null, [
                'widget' => 'single_text',
            ])
            ->add('statut', ChoiceType::class, [
                'choices' => [
                    'Titulaire' => 'titulaire',
                    'ATER' => 'ATER',
                    'Doctorant' => 'Doctorant',
                    'Post-doctorant' => 'Post-doctorant',
                    'Ingénieur' => 'Ingénieur',
                    'Secrétaire' => 'Secrétaire',
                ],
            ])
            ->add('bureau', EntityType::class, [
                'class' => Bureau::class,
                'choice_label' => 'n_bureau',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Personne::class,
        ]);
    }
}
