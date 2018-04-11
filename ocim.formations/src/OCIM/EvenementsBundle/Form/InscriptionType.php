<?php

namespace OCIM\EvenementsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use OCIM\ContactsBundle\Form\PersonneType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class InscriptionType extends AbstractType
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
			->add('stagiaire', new \OCIM\ContactsBundle\Form\PersonneType(), array(
				'data_class' => 'OCIM\ContactsBundle\Entity\Stagiaire',
				//'type'=> new \OCIM\ContactsBundle\Form\PersonneType()
				'em' => $entityManager
				))
			->add('evenementformule', "entity", array(
				'class' => 'OCIM\EvenementsBundle\Entity\evenementFormule',
				"attr" => array('class'=>'width-100'),
				"query_builder" => function(EntityRepository $er) use ($idevenement)
					{
						return $er->createQueryBuilder('u')
						->where('u.evenement = :idevenement')
						->setParameter('idevenement', $idevenement);
					},
				'label' => "Formule et Tarif"
				))
            ->add('dateInscription', 'datetime', array(
				'widget' => 'single_text',
				'format' => 'dd/MM/yyyy HH:mm:ss',
				'required' => false,
				'read_only' => true,
				'disabled' => true,
				'attr' => array('class' => 'width-100'),
				'label' => "Date d'inscription",
				))
            ->add('numberStatut','choice', array(
				'choices'   => array( '1' => 'Validé', '2' => 'En attente', "3" => "Annulé"),
				//'preferred_choices' => array('en attente')
				'attr' => array('class' => 'width-100'),
				"label" => "Statut de l'inscription"
				))
				->add('provenancePCST', null, array(
					'label' => "Provenance PCST",
				))
            ->add('attentes', "textarea", array(
				'attr' => array("class"=>"width-100", 'rows'=> 5),
				'required' => false,
				))
            //->add('statutOrgFinanceur')
            ->add('statutConvention', 'choice', array(
				'choices' => array(
					true => 'OUI',
					false => 'NON',
					),
				'required' => false,
				'empty_value' => "Ne sais pas",
				'label' => "Le stagiaire a-t-il besoin d'une convention?"
			))
            ->add('statutFinancement', 'choice', array(
				'choices' => array(
					true => 'Accordé',
					false => 'NON',
					),
				'required' => false,
				'empty_value' => "En attente",
				'label' => "Statut du financement :"
			))
            //->add('hash')
      ->add('convention', new \OCIM\EvenementsBundle\Form\ConventionType(), array(
				'data_class' => 'OCIM\EvenementsBundle\Entity\Convention',
				'required' => false,
			))
			->add('admin', new \OCIM\ContactsBundle\Form\AdminType(), array(
				'data_class' => 'OCIM\ContactsBundle\Entity\Admin',
				'required' => false,

			))
			->add('signataire', new \OCIM\ContactsBundle\Form\SignataireType(), array(
				'data_class' => 'OCIM\ContactsBundle\Entity\Signataire',
				'required' => false,
				'em' => $entityManager
			))

			->addEventListener(
				FormEvents::POST_SUBMIT,
				function(FormEvent $event) {
					$entity = $event->getForm()->getData();
					$entity->setStatut($event->getForm()->get('numberStatut')->getData());
				}
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
			'attr' => array('class' => 'forms')
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
