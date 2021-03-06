<?php

namespace OCIM\ContactsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use OCIM\ContactsBundle\Form\DataTransformer\StringToTagsTransformer;

class IntervenantType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
		$entityManager = $options['em'];
		$transformer = new StringToTagsTransformer($entityManager);
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
					'attr' => array('class'=> 'width-100'),
					'required' => false
				))
				->add('prenom', 'text', array(
					'attr' => array('class'=> 'width-100'),
					'required' => false
				))
				->add('fonction', 'text', array(
					'attr' => array('class'=> 'width-100'),
					'required' => false
				))
				->add('tel', 'text', array(
					'attr' => array('class'=> 'width-100'),
					'required' => false,
					'label' => 'Téléphone',
				))
				->add('mail', 'text', array(
					'attr' => array('class'=> 'width-100'),
					'required' => false
				))
        ->add('adresse', new AdresseType(),array(
          'required' => false,
          'label' => false,
          'em' => $entityManager
        ))
				->add('commentaire', null, array(
					'attr' => array('class'=> 'width-100'),
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
			'data_class' => 'OCIM\ContactsBundle\Entity\Intervenant',
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
        return 'ocim_contactsbundle_personne';
    }
}
