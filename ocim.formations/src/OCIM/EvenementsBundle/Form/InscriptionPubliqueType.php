<?php

namespace OCIM\EvenementsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use OCIM\ContactsBundle\Form\PersonneType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Validator\Constraints\Email;

class InscriptionPubliqueType extends AbstractType
{
	private $idevenement;

	public function __construct($idevenement)
    {
        $this->idevenement = $idevenement;
    }

        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
		$entityManager = $options['em'];
		$idevenement = $this->idevenement;
        $builder
			->add('evenementformule', "entity", array(
				'class' => 'OCIM\EvenementsBundle\Entity\evenementFormule',
				"attr" => array('class'=>'width-100'),
				"query_builder" => function(EntityRepository $er) use ($idevenement)
					{
						return $er->createQueryBuilder('u')
						->where('u.evenement = :idevenement')
						->setParameter('idevenement', $idevenement);
					},
				'label' => "Tarif(s)",
				'expanded' => true,
				))
			->add(
				$builder->create('stagiaire', 'form', array(
						'required' => true,
						'data_class' => 'OCIM\ContactsBundle\Entity\Stagiaire',
						'label' => false))
					->add('civilite', 'choice', array(
						'choices' => array(
							'Mlle' => 'Mlle',
							'Mme' => 'Mme',
							'Mr' => 'Mr',
							),
						//'empty_value' => 'Choisissez une option',
						'attr' => array('class'=> 'width-100'),
						'required' => true,
						'label' => "Civilité"
					))
					->add('nom', 'text', array(
						'attr' => array('class'=> 'width-100'),
						'required' => true
					))
					->add('prenom', 'text', array(
						'attr' => array('class'=> 'width-100'),
						'required' => true,
						'label' => "Prénom"
					))
					->add('fonction', 'text', array(
						'attr' => array('class'=> 'width-100'),
						'required' => true,
						'label' => "Fonction",
					))
					->add('tel', 'text', array(
						'attr' => array('class'=> 'width-100'),
						'label' => 'Téléphone',
						'required' => true,
					))
					// ->add('fax', 'text', array(
					// 	'required' => false,
					// 	'attr' => array('class'=> 'width-100'),
					// 	'required' => false,
					// ))
					->add('mail', 'text', array(
						'attr' => array('class'=> 'width-100'),
						'label' => "Adresse Mail",
						'required' => true,
						'constraints' => new Email(array(
							'message' => "'{{ value }}' n'est pas une adresse mail valide.",
							'checkMX' => true))
					))
					->add(
						$builder->create("adresse", 'form', array("by_reference"=>false, "label" => false, "data_class" => 'OCIM\ContactsBundle\Entity\Adresse',))
							->add('nomStructure', 'text', array(
								'attr' => array('class' => 'width-100'),
								'label' => 'Nom de la structure',
								'required' => true
							))
							->add('adresse', 'text', array(
								'attr' => array('class' => 'width-100'),
								'label' => 'Adresse',
								'required' => true
							))
							->add('adresseComplement', 'text', array(
								'attr' => array('class' => 'width-100'),
								'label' => "Complément d'adresse",
								'required' => false
							))
							->add('cP', 'text', array(
								'attr' => array('class' => 'width-100'),
								'label' => 'Code postal',
								'required' => true
							))
							->add('ville', 'text', array(
								'attr' => array('class' => 'width-100'),
								'required' => true
							))
							->add('pays', 'text', array(
								'attr' => array('class' => 'width-100'),
								'required' => false
							))
						)
				)
            ->add('attentes', "textarea", array(
				'attr' => array("class"=>"width-100", 'rows'=> 5),
				'required' => true
				))

			->add(
				$builder->create('admin', 'form', array('by_reference' => false, 'label' => false, "data_class"=> 'OCIM\ContactsBundle\Entity\Admin'))
					->add('mail', 'text', array(
						'label' => "Mail du contact administratif",
						'required' => false,
						'constraints' => new Email(array(
							'message' => "'{{ value }}' n'est pas une adresse mail valide.",
							'checkMX' => true))
					))
			)
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'OCIM\EvenementsBundle\Entity\Inscription',
						'attr' => array('class' => 'forms'),
						'csrf_protection' => false,

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
        return 'ocim_evenementsbundle_inscription';
    }
}
