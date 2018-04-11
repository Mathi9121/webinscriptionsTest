<?php

namespace OCIM\EvenementsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * evenementFormule
 */
class evenementFormule
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $modeles;

    /**
     * @var \OCIM\EvenementsBundle\Entity\Formule
     */
    private $formule;

    /**
     * @var \OCIM\EvenementsBundle\Entity\Evenement
     */
    private $evenement;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->modeles = new \Doctrine\Common\Collections\ArrayCollection();
        $this->inscriptions = new \Doctrine\Common\Collections\ArrayCollection();
    }

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
     * Add modeles
     *
     * @param \OCIM\EvenementsBundle\Entity\ModeleChampPerso $modeles
     * @return evenementFormule
     */
    public function addModele(\OCIM\EvenementsBundle\Entity\ModeleChampPerso $modeles)
    {
		if(!$this->modeles->contains($modeles)){
			$this->modeles[] = $modeles;
		}


        return $this;
    }

    /**
     * Remove modeles
     *
     * @param \OCIM\EvenementsBundle\Entity\ModeleChampPerso $modeles
     */
    public function removeModele(\OCIM\EvenementsBundle\Entity\ModeleChampPerso $modeles)
    {
        $this->modeles->removeElement($modeles);
    }

    /**
     * Get modeles
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getModeles()
    {
        return $this->modeles;
    }

    /**
     * Set formule
     *
     * @param \OCIM\EvenementsBundle\Entity\Formule $formule
     * @return evenementFormule
     */
    public function setFormule(\OCIM\EvenementsBundle\Entity\Formule $formule = null)
    {
        $this->formule = $formule;

        return $this;
    }

    /**
     * Get formule
     *
     * @return \OCIM\EvenementsBundle\Entity\Formule
     */
    public function getFormule()
    {
        return $this->formule;
    }

    /**
     * Set evenement
     *
     * @param \OCIM\EvenementsBundle\Entity\Evenement $evenement
     * @return evenementFormule
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

	public function __toString(){
		$tarif = $this->getFormule()->getTarif();
		$tarif = (is_numeric($tarif))? $tarif."€": $tarif;
		return $tarif." --- ".$this->getFormule()->getDescription();
	}
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $inscriptions;


    /**
     * Add inscriptions
     *
     * @param \OCIM\EvenementsBundle\Entity\Inscription $inscriptions
     * @return evenementFormule
     */
    public function addInscription(\OCIM\EvenementsBundle\Entity\Inscription $inscriptions)
    {
        $this->inscriptions[] = $inscriptions;

        return $this;
    }

    /**
     * Remove inscriptions
     *
     * @param \OCIM\EvenementsBundle\Entity\Inscription $inscriptions
     */
    public function removeInscription(\OCIM\EvenementsBundle\Entity\Inscription $inscriptions)
    {
        $this->inscriptions->removeElement($inscriptions);
    }

    /**
     * Get inscriptions
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getInscriptions()
    {
        return $this->inscriptions;
    }

	public function getFormuleId(){
		return $this->getFormule()->getId();
	}
}
