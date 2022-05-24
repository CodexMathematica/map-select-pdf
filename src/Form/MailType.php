<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class MailType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('mailAddress', EmailType::class, [
                'label' => 'Votre adresse email: ',
                'attr' => [
                    'class' => 'form-control'
                ],
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Vous devez saisir votre addresse email.'
                    ]),
                    new Assert\Length([
                        'min' => 2,
                        'max' => 180,
                        'minMessage' => 'L\'adresse mail doit faire au minimum {{ limit }} caractéres de long.',
                        'maxMessage' => 'L\'adresse mail doit faire au maximum  {{ limit }} caractéres de long.'
                    ])
                ]
                ])
            ->add('mark', TextareaType::class, [
                'label' => 'Prendre des notes: ',
                'attr' => [
                    'class' => 'form-control'
                ],
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Vous devez saisir un message.'
                    ]),
                    new Assert\Length([
                        'min' => 10,
                        'max' => 255,
                        'minMessage' => 'Le message doit faire au minimum {{ limit }} caractéres de long.',
                        'maxMessage' => 'Le message doit faire au maximum  {{ limit }} caractéres de long.'
                    ])
                ]
                ])
            ->add('lat', IntegerType::class, [
                'label' => 'Latitude: ',
                'disabled' => true,
                'attr' => [
                    'class' => 'form-control'
                ]
                ])
            ->add('long', IntegerType::class, [
                'label' => 'Longitude: ',
                'disabled' => true,
                'attr' => [
                    'class' => 'form-control'
                ]
                ])
            ->add('city', TextType::class, [
                'label' => 'Ville: ',
                'disabled' => true,
                'attr' => [
                    'class' => 'form-control'
                ]
                ])
            ->add('send', SubmitType::class, [
                'label' => 'Envoyer: ',
                'attr' => [
                    'class' => 'btn btn-primary m-1'
                ]
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
