<?php

namespace OCIM\EvenementsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class ModeleChampPersoType extends AbstractType
{
	public $idevenement;

	function __construct($idevenement){
		$this->idevenement = $idevenement;
	}

        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
		$idevenement = $this->idevenement;
        $builder
			->add('ordre', 'hidden', array(
				'attr' => array('class' => 'ordreModeles'),
				'required'=> true,
			))
            ->add('date', 'date', array(
				'widget' => 'single_text',
				'format' => 'dd/MM/yyyy',
				'attr' => array('placeholder'=>'JJ/MM/AAAA', 'class'=>'datepicker', 'data-tool'=> "datepicker"),
				'required'=> false,
			))
            ->add('description', 'text', array(
				'attr' => array('placeholder' => "Description")
			))
            ->add('typeReponse', 'choice', array(
				'choices'   => array(
					'text'	=> 'Texte',
					'bool'	=> 'Oui/Non',
					//'dateTime'	=> 'Date/Heure',
				),
				'empty_value' => 'Type de réponse',
				'required' => true,
			))
			->add('evenementFormule', 'entity', array(
				'class' => 'OCIM\EvenementsBundle\Entity\evenementFormule',
				'multiple' => true,
				'expanded' => true,
				'by_reference' => false,
				'query_builder' => function(EntityRepository $er) use($idevenement)
					{
						return $er->createQueryBuilder('u')
						->where('u.evenement = :idevenement')
						->setParameter('idevenement', $idevenement);
					},
				'property' => "FormuleId",
			))
			->add('intervenant', 'checkbox', array(
				'label' => 'Intervenant',
				'required' => false,
			));
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'OCIM\EvenementsBundle\Entity\ModeleChampPerso',
			'attr' => array('class'=> 'forms'),
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ocim_evenementsbundle_modelechampPerso';
    }
}
