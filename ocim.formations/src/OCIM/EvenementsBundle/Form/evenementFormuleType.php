<?php

namespace OCIM\EvenementsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class evenementFormuleType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('formule', 'entity', array(
				'class' => 'OCIM\EvenementsBundle\Entity\Formule',
				'label'=> false,
				'attr'=> array('class'=>'width-100'),
			))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'OCIM\EvenementsBundle\Entity\evenementFormule'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ocim_evenementsbundle_evenementformule';
    }
}
