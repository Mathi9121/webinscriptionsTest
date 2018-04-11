<?php

namespace OCIM\ContactsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use OCIM\ContactsBundle\Form\DataTransformer\StringToTypePersonneTransformer;

class SignataireType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
		$entityManager = $options['em'];
        $builder
            ->add('civilite', 'choice', array(
				'choices' => array(
					'Mlle' => 'Mlle',
					'Mme' => 'Mme',
					'Mr' => 'Mr',
					),
				'attr' => array('class'=> 'width-100'),
				'required' => false
			))
            ->add('nom', 'text', array(
				'attr' => array('class'=> 'width-100')
			))
            ->add('prenom', 'text', array(
				'attr' => array('class'=> 'width-100')
			))
            ->add('fonction', 'text', array(
				'attr' => array('class'=> 'width-100')
			))
            ->add('mail', 'text', array(
				'attr' => array('class'=> 'width-100')
			))
			->add('adresse', new AdresseSignataireType(), array(
				'attr' => array('class'=> 'width-100'),
				'data_class' => 'OCIM\ContactsBundle\Entity\Adresse',
				'em' => $entityManager
			))
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'OCIM\ContactsBundle\Entity\Signataire',
			'attr' => array('class'=> 'forms'),
        ));
		$resolver->setRequired(array(
            'em',
        ));

        $resolver->setAllowedTypes(array(
            'em' => 'Doctrine\Common\Persistence\ObjectManager',
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ocim_contactsbundle_signataire';
    }
}
