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
            ->add('username', TextType::class,[
                'required' => true,
                'constraints' => [new Assert\Length(min: 3, max: 50)],
            ])
            ->add('firstname', TextType::class,[
                'required' => true,
                'constraints' => [new Assert\Length(min: 3, max: 50)],
            ])
            ->add('lastname', TextType::class,[
                'required' => true,
                'constraints' => [new Assert\Length(min: 3, max: 50)],
            ])
            ->add('phone', TextType::class,[
                'required' => true,
                'constraints' => [new Assert\Length(min: 10)],
            ])
            ->add('email')
            ->add('Password', PasswordType::class, [
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank(
                        message: 'Please enter a password',
                    ),
                    new Length(
                        min: 6,
                        max: 4096,
                        minMessage: 'Your password should be at least {{ limit }} characters',
                    ),
                ],
            ])
            ->add('active', CheckboxType::class)
            ->add('profilePicture', FileType::class, [
                'label' => 'Photo de profile (png,jpeg)',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new Assert\File(
                        maxSize: '1024k',
                        extensions: ['pdf','png','jpeg', 'jpg'],
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
        ]);
    }
}
