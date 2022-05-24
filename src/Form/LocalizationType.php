<?php

namespace App\Form;

use App\Service\LocalizationService;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class LocalizationType extends AbstractType
{

    private $localizationService;

    public function __construct(LocalizationService $localizationService)
    {
        $this->localizationService = $localizationService;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder
            ->add('zip', IntegerType::class, [
                'label' => 'Code postal: ',
                'attr' => [
                    'class' => 'form-control'
                ],
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Un code postal doit être saisi.'
                    ]),
                    new Assert\Length([
                        'min' => 4,
                        'max' => 6,
                        'minMessage' => 'Le code postal doit faire au minimum {{ limit }} chiffres de long.',
                        'maxMessage' => 'Le code postal doit faire au maximum  {{ limit }} chiffres de long.'
                    ])
                ]
            ])
            ->add('city', ChoiceType::class, [
                'label' => 'Ville: ',
                'placeholder' => 'Necessite de saisir un code postal valide',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('send', SubmitType::class, [
                'label' => 'Soumettre',
                'attr' => [
                    'class' => 'btn btn-primary m-1'
                ]
            ])
            ;

            //Pour rendre le select dynamique
            $formModifier = function(FormInterface $form, string $zip = null) {
                $cities = (null === $zip) ? [] : $this->localizationService->getCities($zip); 
                $form->add('city', ChoiceType::class, [
                    'placeholder' => 'Ville (necessite de saisir un code postal)',
                    'choices' => $cities,
                    'attr' => [
                        'class' => 'form-control'
                    ]
                ]);
            };
    
            $builder->get('zip')->addEventListener(
                FormEvents::POST_SUBMIT,
                function (FormEvent $event) use ($formModifier) {
                    $zip = $event->getForm()->getData();
                    if(strlen($zip) === 5){
                        $formModifier($event->getForm()->getParent(), $zip);
                    }
                }
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
