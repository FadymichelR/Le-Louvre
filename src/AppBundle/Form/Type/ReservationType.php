<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType; 
use Symfony\Component\Form\Extension\Core\Type\CollectionType; 

class ReservationType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('dateVisit',      DateTimeType::class, array(
          'label' => 'Date de la visite',
            'widget' => 'single_text',
            'format' => 'dd/MM/yyyy',
            'attr' => ['class' => 'w3-input w3-border w3-hover-border-tr datepicker',
            'placeholder' =>'Selectioné une date',
            ]
        ))
        ->add('type',      ChoiceType::class, array(
          'label' => 'Type du billet souhaité',
            'choices'  => array(
                'Journée' => 1,
                'Demi-Journée' => 0.5,),
            'attr' => ['class' => 'w3-select w3-border',]
            ))
      ->add('billets', CollectionType::class, array(
        'entry_type'   => BilletType::class,
        'allow_add'    => true,
        'allow_delete' => true
      ))
      ->add('email',TextType::class, array('label' => 'Votre adresse email', 'attr' => ['class' => 'w3-input w3-border w3-hover-border-tr',]))
      ->add('send',      SubmitType::class, array('label' => 'Effectuer la réservation', 'attr' => ['class' => 'w3-button w3-right w3-green w3-section',
            ])
            )
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Reservation'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_reservation';
    }


}
