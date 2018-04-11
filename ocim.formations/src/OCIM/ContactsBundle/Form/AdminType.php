<?php

namespace OCIM\ContactsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AdminType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
		
        $builder
            ->add('civilite', 'choice', array(
				'choices' => array(
					'Mlle' => 'Mlle',
					'Mme' => 'Mme',
					'Mr' => 'Mr',
					),
				'attr' => array('class'=> 'width-100'),
				'label' => 'Civilité',
				'required' => false,
			))
            ->add('nom', 'text', array(
				'attr' => array('class'=> 'width-100'),
				'required' => false,
			))
            ->add('prenom', 'text', array(
				'attr' => array('class'=> 'width-100'),
				'label' => 'Prénom',
				'required' => false,
			))
            ->add('fonction', 'text', array(
				'attr' => array('class'=> 'width-100'),
				'required' => false,
			))
            ->add('tel', 'text', array(
				'attr' => array('class'=> 'width-100'),
				'label' => 'Téléphone',
				'required' => false,
			))
            ->add('mail', 'text', array(
				'attr' => array('class'=> 'width-100'),
				'label' => 'Adresse Mail',
				'required' => false,
			))
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'OCIM\ContactsBundle\Entity\Admin',
			'attr' => array('class'=> 'forms'),
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ocim_contactsbundle_admin';
    }
}
