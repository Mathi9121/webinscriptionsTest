<?php

namespace OCIM\EvenementsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ReponsesChampPerso
 */
class ReponsesChampPerso
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var boolean
     */
    private $reponse;

    /**
     * @var string
     */
    private $reponseText;

    /**
     * @var \DateTime
     */
    private $date;
	

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
     * Set reponse
     *
     * @param boolean $reponse
     * @return ReponsesChampPerso
     */
    public function setReponse($reponse)
    {
        $this->reponse = $reponse;

        return $this;
    }

    /**
     * Get reponse
     *
     * @return boolean 
     */
    public function getReponse()
    {
        return $this->reponse;
    }

    /**
     * Set reponseText
     *
     * @param string $reponseText
     * @return ReponsesChampPerso
     */
    public function setReponseText($reponseText)
    {
        $this->reponseText = $reponseText;

        return $this;
    }

    /**
     * Get reponseText
     *
     * @return string 
     */
    public function getReponseText()
    {
        return $this->reponseText;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return ReponsesChampPerso
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
  
  
  
    private $modele;


    /**
     * Set modele
     *
     * @param \OCIM\EvenementsBundle\Entity\ModeleChampPerso $modele
     * @return ReponsesChampPerso
     */
    public function setModele(\OCIM\EvenementsBundle\Entity\ModeleChampPerso $modele = null)
    {
        $this->modele = $modele;

        return $this;
    }

    /**
     * Get modele
     *
     * @return \OCIM\EvenementsBundle\Entity\ModeleChampPerso 
     */
    public function getModele()
    {
        return $this->modele;
    }

	
    private $inscription;


    /**
     * Set inscription
     *
     * @param \OCIM\EvenementsBundle\Entity\Inscription $inscription
     * @return ReponsesChampPerso
     */
    public function setInscription(\OCIM\EvenementsBundle\Entity\Inscription $inscription = null)
    {
        $this->inscription = $inscription;

        return $this;
    }

    /**
     * Get inscription
     *
     * @return \OCIM\EvenementsBundle\Entity\Inscription 
     */
    public function getInscription()
    {
        return $this->inscription;
    }
    /**
     * @var \OCIM\ContactsBundle\Entity\Personne
     */
    private $personne;


    /**
     * Set personne
     *
     * @param \OCIM\ContactsBundle\Entity\Personne $personne
     * @return ReponsesChampPerso
     */
    public function setPersonne(\OCIM\ContactsBundle\Entity\Personne $personne = null)
    {
        $this->personne = $personne;

        return $this;
    }

    /**
     * Get personne
     *
     * @return \OCIM\ContactsBundle\Entity\Personne 
     */
    public function getPersonne()
    {
        return $this->personne;
    }
}
