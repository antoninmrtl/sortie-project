<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Form\Model\QuestSearch;

class QuestSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('isPromoter', CheckboxType::class, [
                'label' => "Quêtes dont je suis l'organisateur",
                'required' => false,
            ])
            ->add('isRegistered', CheckboxType::class, [
                'label' => "Quêtes auxquelles je suis inscrit",
                'required' => false,
            ])
            ->add('startDate', DateTimeType::class, [
                'required' => false,
                'widget' => 'single_text',
                'label' => 'Entre le...',
            ])
            ->add('endDate', DateTimeType::class, [
                'required' => false,
                'widget' => 'single_text',
                'label' => 'Et le...',
            ]);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => QuestSearch::class,
            'method' => 'GET',
            'allow_extra_fields' => true
        ]);
    }
}

