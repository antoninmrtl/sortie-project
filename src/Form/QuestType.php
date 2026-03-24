<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\Place;
use App\Entity\Quest;
use App\Entity\Status;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuestType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('startDateTime')
            ->add('duration')
            ->add('inscriptionLimitDate')
            ->add('nbMaxInscription')
            ->add('infoQuest')
//            JE PENSE PAS QU'IL FAUT LES INDIQUER
//            ->add('status', EntityType::class, [
//                'class' => Status::class,
//                'choice_label' => 'id',
//            ])
            ->add('campus', EntityType::class, [
                'class' => Campus::class,
                'choice_label' => 'id',
            ])
            ->add('place', EntityType::class, [
                'class' => Place::class,
                'choice_label' => 'id',
            ])
            //            JE PENSE PAS QU'IL FAUT LES INDIQUER
//            ->add('users', EntityType::class, [
//                'class' => User::class,
//                'choice_label' => 'id',
//                'multiple' => true,
//            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Quest::class,
        ]);
    }
}
