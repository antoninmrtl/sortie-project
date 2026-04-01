<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\Place;
use App\Entity\Quest;
use App\Entity\Status;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class QuestType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name',TextType::class, [
                'label'=>'Nom'
            ])
            ->add('startDateTime', DateTimeType::class, [
                'label'=>'Date et Heure de début'
            ])
            ->add('duration', IntegerType::class,[
                'label'=>'Durée'
            ])
            ->add('inscriptionLimitDate', DateTimeType::class, [
                'label'=>"Date limite d'inscription"
            ])
            ->add('nbMaxInscription', IntegerType::class, [
                'label'=>"Nombre maximum d'inscription"
            ])
            ->add('infoQuest', TextareaType::class, [
                'label'=>'Description'
            ])
            ->add('campus', EntityType::class, [
                'class' => Campus::class,
                'choice_label' => 'name',
            ])
            ->add('place', EntityType::class, [
                'label'=>'Lieu',
                'class' => Place::class,
                'choice_label' => 'name',
            ])
            ->add('picture', FileType::class, [
                'label' => 'Photo de l\'evenement (png,jpeg)',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new Assert\File(
                        maxSize: '1024k',
                        extensions: ['pdf','png','jpeg', 'jpg'],
                        extensionsMessage: 'Please upload a valid image / document',
                    )
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Quest::class,
        ]);
    }
}
