<?php

namespace OCIM\EvenementsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ModeleChampPerso
 */
class ModeleChampPerso
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var \DateTime
     */
    private $date;

    /**
     * @var string
     */
    private $description;

    /**
     * @var string
     */
    private $typeReponse;


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
     * Set date
     *
     * @param \DateTime $date
     * @return ModeleChampPerso
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return ModeleChampPerso
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set typeReponse
     *
     * @param string $typeReponse
     * @return ModeleChampPerso
     */
    public function setTypeReponse($typeReponse)
    {
        $this->typeReponse = $typeReponse;

        return $this;
    }

    /**
     * Get typeReponse
     *
     * @return string 
     */
    public function getTypeReponse()
    {
        return $this->typeReponse;
    }
 
	
	public function __toString(){
		return $this->date->format('Y m d')."  ".$this->description." ".$this->typeReponse;
	}
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $reponses;

    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->reponses = new \Doctrine\Common\Collections\ArrayCollection();
        $this->evenementFormule = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add reponses
     *
     * @param \OCIM\EvenementsBundle\Entity\ReponsesChampPerso $reponses
     * @return ModeleChampPerso
     */
    public function addReponse(\OCIM\EvenementsBundle\Entity\ReponsesChampPerso $reponses)
    {
        $this->reponses[] = $reponses;

        return $this;
    }

    /**
     * Remove reponses
     *
     * @param \OCIM\EvenementsBundle\Entity\ReponsesChampPerso $reponses
     */
    public function removeReponse(\OCIM\EvenementsBundle\Entity\ReponsesChampPerso $reponses)
    {
        $this->reponses->removeElement($reponses);
    }

    /**
     * Get reponses
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getReponses()
    {
        return $this->reponses;
    }

 
    /**
     * @var integer
     */
    private $ordre;


    /**
     * Set ordre
     *
     * @param integer $ordre
     * @return ModeleChampPerso
     */
    public function setOrdre($ordre)
    {
        $this->ordre = $ordre;

        return $this;
    }

    /**
     * Get ordre
     *
     * @return integer 
     */
    public function getOrdre()
    {
        return $this->ordre;
    }

	
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $evenementFormule;


    /**
     * Add evenementFormule
     *
     * @param \OCIM\EvenementsBundle\Entity\evenementFormule $evenementFormule
     * @return ModeleChampPerso
     */
    public function addEvenementFormule(\OCIM\EvenementsBundle\Entity\evenementFormule $evenementFormule)
    {
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
   
   
    /**
     * @var \OCIM\EvenementsBundle\Entity\Evenement
     */
    private $evenement;


    /**
     * Set evenement
     *
     * @param \OCIM\EvenementsBundle\Entity\Evenement $evenement
     * @return ModeleChampPerso
     */
    public function setEvenement(\OCIM\EvenementsBundle\Entity\Evenement $evenement = null)
    {
        $this->evenement = $evenement;

        return $this;
    }

    /**
     * Get evenement
     *
     * @return \OCIM\EvenementsBundle\Entity\Evenement 
     */
    public function getEvenement()
    {
        return $this->evenement;
    }
	
	private $intervenant;
	
	public function setIntervenant($bool){
		$this->intervenant = $bool;
		return $this;
	}
	
	public function getIntervenant(){
		
		return $this->intervenant;
	}
	
	public function nombreReponsesPositives(){
		$nb = $this->getReponses()->filter(function($rep){
			if($rep->getReponse()) {return true;}
		});
		return $nb->count();
	}
}
