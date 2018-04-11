<?php

namespace OCIM\EvenementsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ConventionType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('numero', 'text', array(
				'required' => false,
				'attr'=> array('class'=>'input-big text-centered','size' => 3, 'style'=> 'margin: 0 auto !important;' )

				))
            ->add('edition', 'date', array(
				'label' => "Date d'édition",
				'widget' => 'single_text',
				'required' => false,
				'attr'=> array('class'=>'input-big text-centered', 'data-tool' => 'datepicker', 'style'=> 'margin: 0 auto !important;'),
				'format' => 'dd/MM/yyyy'
				))
            ->add('etape1', 'date', array(
				'widget' => 'single_text',
				'required' => false,
				'label' => "Etape 1",
				'format' => 'dd/MM/yyyy',
        'attr'=> array('data-tool' => 'datepicker'),
				))
            ->add('etape2', 'date', array(
				'widget' => 'single_text',
				'required' => false,
				'label' => "Etape 2",
				'format' => 'dd/MM/yyyy',
        'attr'=> array('data-tool' => 'datepicker'),
				))
            ->add('etape3', 'date', array(
				'widget' => 'single_text',
				'required' => false,
				'label' => "Etape 3",
				'format' => 'dd/MM/yyyy',
        'attr'=> array('data-tool' => 'datepicker'),
				))
            ->add('etape4', 'date', array(
				'widget' => 'single_text',
				'required' => false,
				'label' => "Etape 4",
				'format' => 'dd/MM/yyyy',
        'attr'=> array('data-tool' => 'datepicker'),
				))
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'OCIM\EvenementsBundle\Entity\Convention',
			'attr' => array('class'=> "forms")
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ocim_evenementsbundle_convention';
    }
}
