<?php

namespace OCIM\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', 'text', array(
				'label'=> 'Nom',
			))
            ->add('prenom', 'text', array(
				'label'=> 'Prénom',
			))
			->add('mail', 'text', array(
				'label'=> '',
			))
            ->add('login', 'text', array(
				'label'=> 'Login',
			))
            ->add('password', 'password', array(
				'label'=> 'Mot de passe',
			))
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'OCIM\UserBundle\Entity\User',
			'attr' => array('class'=> 'forms'),

        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ocim_userbundle_user';
    }
}
