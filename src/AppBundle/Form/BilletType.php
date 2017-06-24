<?php

namespace AppBundle\Form;

use Symfony\Component\Form\{AbstractType,FormBuilderInterface};
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;

class BilletType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('nom',TextType::class, array('attr' => ['class' => 'w3-input w3-border w3-hover-border-tr',]))
        ->add('prenom',TextType::class, array('attr' => ['class' => 'w3-input w3-border w3-hover-border-tr',]))
        ->add('pays', CountryType::class, array(
            'error_mapping' => array(
            'matchingCityAndZipCode' => 'city',
        ),
            'attr' => ['class' => 'w3-select w3-border',]
            ))
        ->add('birth', BirthdayType::class, array(
            'label'    => 'Date de naissance',
            'placeholder' => array(
                'year' => 'Année', 'month' => 'Mois', 'day' => 'Jour',
            ),
            'format' => 'dd-MM-yyyy',
            )
        )
        ->add('reduced_price', CheckboxType::class, array(
            'label'    => 'Tarif Réduit ',
                'required' => false,
        'attr' => ['class' => 'w3-check',]
))
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Billet'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_billet';
    }


}
