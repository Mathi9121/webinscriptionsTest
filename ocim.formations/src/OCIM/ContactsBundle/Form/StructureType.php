<?php

namespace OCIM\ContactsBundle\Form;

use OCIM\ContactsBundle\Form\DataTransformer\StringToTagsTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class StructureType extends AbstractType
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
            ->add('type', null, array(
				'attr' => array('class'=>'width-100'),
				'required' => false,
				'query_builder' => function(EntityRepository $repository) { 
					return $repository->createQueryBuilder('u')->orderBy('u.type', 'ASC');
				}
			))
            ->add(
				$builder->create('tags', 'text', array(
				'attr' => array('class'=>'width-100'),
				'required' => false,
				// 'data_class' => 'OCIM\ContactsBundle\Entity\TagStructure'
			))->addModelTransformer($transformer))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'OCIM\ContactsBundle\Entity\Structure'
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
        return 'ocim_contactsbundle_structure';
    }
}
