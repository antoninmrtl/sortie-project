<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\Quest;
use App\Entity\User;
use Doctrine\DBAL\Types\BooleanType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints as Assert;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class, [
                'label'=>"Nom d'aventurier",
                'required' => true,
                'constraints' => [new Assert\Length(min: 3, max: 50)],
            ])
            ->add('firstname', TextType::class, [
                'label'=>'Prénom',
                'required' => true,
                'constraints' => [new Assert\Length(min: 3, max: 50)],
            ])
            ->add('lastname', TextType::class, [
                'label'=>'Nom de famille',
                'required' => true,
                'constraints' => [new Assert\Length(min: 3, max: 50)],
            ])
            ->add('phone', TextType::class, [
                'label'=>"Téléphone",
                'required' => true,
                'constraints' => [new Assert\Length(min: 10)],
            ])
            ->add('email')
            ->add('Password', RepeatedType::class, [
                'label'=>"Mot de passe",
                'type' => PasswordType::class,
                'mapped' => false,
                'required' => false,
                'invalid_message' => 'Les parchemins de mots de passe doivent être identiques !',
                'options' => ['attr' => ['class' => 'rounded-lg bg-gray-100 px-4 py-2 w-full text-black']],
                'first_options'  => ['label' => 'Nouveau mot de passe'],
                'second_options' => ['label' => 'Confirmez le mot de passe'],
                'constraints' => $options['is_edit'] ? [] : [
                    new NotBlank(['message' => 'L\'aventurier doit avoir un mot de passe !']),
                    new Length(['min' => 6]),
                ],
            ])
            ->add('active', CheckboxType::class)
            ->add('profilePicture', FileType::class, [
                'label' => 'Photo de profil (png,jpeg)',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new Assert\File(
                        maxSize: '2048k',
                        extensions: ['pdf', 'png', 'jpeg', 'jpg'],
                        extensionsMessage: 'Please upload a valid image / document',
                    )
                ],
            ])
            ->add('campus', EntityType::class, [
                'class' => Campus::class,
                'choice_label' => 'name',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'is_edit' => false
        ]);

        $resolver->setAllowedTypes('is_edit', 'bool');
    }
}
