<?php

namespace OCIM\EvenementsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use OCIM\EvenementsBundle\Entity\TypeEvenement;
use OCIM\EvenementsBundle\Entity\ModeleChampPerso;

/**
 * Evenement
 */
class Evenement
{

	protected $type;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $intitule;

    /**
     * @var string
     */
    private $lieu;

    /**
     * @var \DateTime
     */
    private $dateDebut;

    /**
     * @var \DateTime
     */
    private $dateFin;

    /**
     * @var string
     */
    private $dateText;

    /**
     * @var decimal
     */
    private $nbHeures;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set intitule
     *
     * @param string $intitule
     * @return Evenement
     */
    public function setIntitule($intitule)
    {
        $this->intitule = $intitule;

        return $this;
    }

    /**
     * Get intitule
     *
     * @return string
     */
    public function getIntitule()
    {
        return $this->intitule;
    }

    /**
     * Set lieu
     *
     * @param string $lieu
     * @return Evenement
     */
    public function setLieu($lieu)
    {
        $this->lieu = $lieu;

        return $this;
    }

    /**
     * Get lieu
     *
     * @return string
     */
    public function getLieu()
    {
        return $this->lieu;
    }

    /**
     * Set dateDebut
     *
     * @param \DateTime $dateDebut
     * @return Evenement
     */
    public function setDateDebut($dateDebut)
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    /**
     * Get dateDebut
     *
     * @return \DateTime
     */
    public function getDateDebut()
    {
        return $this->dateDebut;
    }

    /**
     * Set dateFin
     *
     * @param \DateTime $dateFin
     * @return Evenement
     */
    public function setDateFin($dateFin)
    {
        $this->dateFin = $dateFin;

        return $this;
    }

    /**
     * Get dateFin
     *
     * @return \DateTime
     */
    public function getDateFin()
    {
        return $this->dateFin;
    }

    /**
     * Set dateText
     *
     * @param string $dateText
     * @return Evenement
     */
    public function setDateText($dateText)
    {
        $this->dateText = $dateText;

        return $this;
    }

    /**
     * Get dateText
     *
     * @return string
     */
    public function getDateText()
    {
        return $this->dateText;
    }

    /**
     * Set nbHeures
     *
     * @param integer $nbHeures
     * @return Evenement
     */
    public function setNbHeures($nbHeures)
    {
        $this->nbHeures = $nbHeures;

        return $this;
    }

    /**
     * Get nbHeures
     *
     * @return integer
     */
    public function getNbHeures()
    {
        return $this->nbHeures;
    }

    /**
     * Set type
     *
     * @param \OCIM\EvenementsBundle\Entity\TypeEvenement $type
     * @return Evenement
     */
    public function setType(\OCIM\EvenementsBundle\Entity\TypeEvenement $type = null)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return \OCIM\EvenementsBundle\Entity\TypeEvenement
     */
    public function getType()
    {
        return $this->type;
    }


	/**
     * To string
     *
     * @return String
     */
    public function __toString()
    {
        return $this->getIntitule()." | ".$this->getLieu();
    }

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $evenementFormule;
	private $modeles;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->evenementFormule = new \Doctrine\Common\Collections\ArrayCollection();
        $this->modeles = new \Doctrine\Common\Collections\ArrayCollection();

    }

    /**
     * Add evenementFormule
     *
     * @param \OCIM\EvenementsBundle\Entity\evenementFormule $evenementFormule
     * @return Evenement
     */
    public function addEvenementFormule(\OCIM\EvenementsBundle\Entity\evenementFormule $evenementFormule)
    {
		$evenementFormule->setEvenement($this);
        $this->evenementFormule[] = $evenementFormule;

        return $this;
    }

    /**
     * Remove evenementFormule
     *
     * @param \OCIM\EvenementsBundle\Entity\evenementFormule $evenementFormule
     */
    public function removeEvenementFormule(\OCIM\EvenementsBundle\Entity\evenementFormule $evenementFormule)
    {
        $this->evenementFormule->removeElement($evenementFormule);
    }

    /**
     * Get evenementFormule
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEvenementFormule()
    {
        return $this->evenementFormule;
    }

	public function getFormules()
    {
		$formules = new ArrayCollection();
        foreach($this->evenementFormule as $ff){
			$formules->add($ff->getFormule());
		}
		return $formules;
    }
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $intervenants;


    /**
     * Add intervenants
     *
     * @param \OCIM\ContactsBundle\Entity\Intervenant $intervenants
     * @return Evenement
     */
    public function addIntervenant(\OCIM\ContactsBundle\Entity\Intervenant $intervenants)
    {
        $this->intervenants[] = $intervenants;

        return $this;
    }

    /**
     * Remove intervenants
     *
     * @param \OCIM\ContactsBundle\Entity\Intervenant $intervenants
     */
    public function removeIntervenant(\OCIM\ContactsBundle\Entity\Intervenant $intervenants)
    {
        $this->intervenants->removeElement($intervenants);
    }

    /**
     * Get intervenants
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getIntervenants()
    {
        return $this->intervenants;
    }

	public function getModeles(){
		return $this->modeles;
	}

	public function addModele(\OCIM\EvenementsBundle\Entity\ModeleChampPerso $ml)
    {
		$this->modeles[] = $ml;
        return $this;
    }

	public function removeModele(\OCIM\EvenementsBundle\Entity\ModeleChampPerso $ml)
    {
        $this->modeles->removeElement($ml);
    }

	public function setModeles(ArrayCollection $modeles){
		$this->modeles = $modeles;

		foreach($this->modeles as $modele){
			if($modele->getEvenement() != null){
				$modele->setIntervenant(true);
			}
		}

		return $this;
	}
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $modelesIntervenants;


    /**
     * Add modelesIntervenants
     *
     * @param \OCIM\EvenementsBundle\Entity\ModeleChampPerso $modelesIntervenants
     * @return Evenement
     */
    public function addModelesIntervenant(\OCIM\EvenementsBundle\Entity\ModeleChampPerso $modelesIntervenants)
    {
        $this->modelesIntervenants[] = $modelesIntervenants;

        return $this;
    }

    /**
     * Remove modelesIntervenants
     *
     * @param \OCIM\EvenementsBundle\Entity\ModeleChampPerso $modelesIntervenants
     */
    public function removeModelesIntervenant(\OCIM\EvenementsBundle\Entity\ModeleChampPerso $modelesIntervenants)
    {
        $this->modelesIntervenants->removeElement($modelesIntervenants);
    }

    /**
     * Get modelesIntervenants
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getModelesIntervenants()
    {
        return $this->modelesIntervenants;
    }
    /**
     * @var string
     */
    private $nbJours;


    /**
     * Set nbJours
     *
     * @param string $nbJours
     * @return Evenement
     */
    public function setNbJours($nbJours)
    {
        $this->nbJours = $nbJours;

        return $this;
    }

    /**
     * Get nbJours
     *
     * @return string
     */
    public function getNbJours()
    {
        return $this->nbJours;
    }


		private $eventType;

		public function getEventType(){
			if($this->eventType == null){
				$Rclass = new \ReflectionClass($this);
				return strtolower($Rclass->getShortName());
			}
			else return $this->eventType;
		}

		public function setEventType($str = null){
			$this->eventType = $str;
		}

		public function getPrettyEventType($multiple = false){
			$index = $this->getEventType();
			$index .= ($multiple)? 's': '';
			$eventTypes = array(
				"events" => "&Eacute;vénements",
				"event" => "&Eacute;vénement",
				"formation" => "Formation",
				"formations" => "Formations",
			);
			return $eventTypes[$index];
		}

}
