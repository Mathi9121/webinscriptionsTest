<?php

namespace OCIM\ContactsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Personne
 */
class Personne
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $civilite;

    /**
     * @var string
     */
    private $nom;

    /**
     * @var string
     */
    private $prenom;

    /**
     * @var string
     */
    private $fonction;

    /**
     * @var string
     */
    private $tel;

    /**
     * @var string
     */
    private $mail;

    /**
     * @var string
     */


	private $type;

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
     * Set civilite
     *
     * @param string $civilite
     * @return Personne
     */
    public function setCivilite($civilite)
    {
        $this->civilite = $civilite;

        return $this;
    }

    /**
     * Get civilite
     *
     * @return string
     */
    public function getCivilite()
    {
        return $this->civilite;
    }

    /**
     * Set nom
     *
     * @param string $nom
     * @return Personne
     */
    public function setNom($nom)
    {
        $this->nom = mb_strtoupper($nom);

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set prenom
     *
     * @param string $prenom
     * @return Personne
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * Get prenom
     *
     * @return string
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * Set fonction
     *
     * @param string $fonction
     * @return Personne
     */
    public function setFonction($fonction)
    {
        $this->fonction = $fonction;

        return $this;
    }

    /**
     * Get fonction
     *
     * @return string
     */
    public function getFonction()
    {
        return $this->fonction;
    }

    /**
     * Set tel
     *
     * @param string $tel
     * @return Personne
     */
    public function setTel($tel)
    {
        $this->tel = $tel;

        return $this;
    }

    /**
     * Get tel
     *
     * @return string
     */
    public function getTel()
    {
        return $this->tel;
    }

    /**
     * Set mail
     *
     * @param string $mail
     * @return Personne
     */
    public function setMail($mail)
    {
        $this->mail = $mail;

        return $this;
    }

    /**
     * Get mail
     *
     * @return string
     */
    public function getMail()
    {
        return $this->mail;
    }


    public function getType()
    {
        $Rclass = new \ReflectionClass($this);
        return strtolower($Rclass->getShortName());
    }

    /**
     * @var string
     */
    private $fax;


    /**
     * Set fax
     *
     * @param string $fax
     * @return Personne
     */
    public function setFax($fax)
    {
        $this->fax = $fax;

        return $this;
    }

    /**
     * Get fax
     *
     * @return string
     */
    public function getFax()
    {
        return $this->fax;
    }
    /**
     * @var \OCIM\ContactsBundle\Entity\Adresse
     */
    private $adresse;


    /**
     * Set adresse
     *
     * @param \OCIM\ContactsBundle\Entity\Adresse $adresse
     * @return Personne
     */
    public function setAdresse(\OCIM\ContactsBundle\Entity\Adresse $adresse = null)
    {
        $this->adresse = $adresse;

        return $this;
    }

    /**
     * Get adresse
     *
     * @return \OCIM\ContactsBundle\Entity\Adresse
     */
    public function getAdresse()
    {
        return $this->adresse;
    }

	public function __toString(){
		return $this->prenom;
	}
    /**
     * @var \OCIM\EvenementsBundle\Entity\Inscription
     */
    private $inscription;


    /**
     * Set inscription
     *
     * @param \OCIM\EvenementsBundle\Entity\Inscription $inscription
     * @return Personne
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
     * @var \Doctrine\Common\Collections\Collection
     */
    private $intervenant;

    /**
     * Constructor
     */
    public function __construct($type = null)
    {
        $this->intervenant = new ArrayCollection();
        $this->inscription = new ArrayCollection();
		    $this->type = $type;
    }

    /**
     * Add intervenant
     *
     * @param \OCIM\ContactsBundle\Entity\Intervenant $intervenant
     * @return Personne
     */
    public function addIntervenant(\OCIM\ContactsBundle\Entity\Intervenant $intervenant)
    {
        $this->intervenant[] = $intervenant;

        return $this;
    }

    /**
     * Remove intervenant
     *
     * @param \OCIM\ContactsBundle\Entity\Intervenant $intervenant
     */
    public function removeIntervenant(\OCIM\ContactsBundle\Entity\Intervenant $intervenant)
    {
        $this->intervenant->removeElement($intervenant);
    }

    /**
     * Get intervenant
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getIntervenant()
    {
        return $this->intervenant;
    }

	private $commentaire;

	/**
     * Set commentaire
     *
     * @param string $commentaire
     * @return Personne
     */
    public function setCommentaire($commentaire)
    {
        $this->commentaire = $commentaire;

        return $this;
    }

    /**
     * Get commentaire
     *
     * @return $commentaire
     */
    public function getCommentaire()
    {
        return $this->commentaire;
    }

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $reponsesChampPerso;


    /**
     * Add reponsesChampPerso
     *
     * @param \OCIM\EvenementsBundle\Entity\ReponsesChampPerso $reponsesChampPerso
     * @return Personne
     */
    public function addReponsesChampPerso(\OCIM\EvenementsBundle\Entity\ReponsesChampPerso $reponsesChampPerso)
    {
        $this->reponsesChampPerso[] = $reponsesChampPerso;

        return $this;
    }

    /**
     * Remove reponsesChampPerso
     *
     * @param \OCIM\EvenementsBundle\Entity\ReponsesChampPerso $reponsesChampPerso
     */
    public function removeReponsesChampPerso(\OCIM\EvenementsBundle\Entity\ReponsesChampPerso $reponsesChampPerso)
    {
        $this->reponsesChampPerso->removeElement($reponsesChampPerso);
    }

    /**
     * Get reponsesChampPerso
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getReponsesChampPerso()
    {
        return $this->reponsesChampPerso;
    }

	public function getReponseByModeleId($modeleId, $return = null){

		$reponse =  $this->getReponsesChampPerso()->filter(
			function($rl) use ($modeleId){
				if($rl->getModele()->getId() == $modeleId){
					return true;
				}
			}
		)->first();

		if($reponse){
			if(!is_null($return)){
				if($return == 'bool'){
					return ($reponse->getReponse())? '1' : '0' ;
				}
				else return $reponse->getReponseText();
			}
			else{
				return $reponse;
			}
		}
		else return null;
	}

  public function getCivilitepretty(){
    $array = array(
      'Mme' => 'Madame',
      'Mr' => 'Monsieur',
      'Mlle' => 'Mademoiselle',
    );
    return $array[$this->getCivilite()];
  }


    /**
     * Add inscription
     *
     * @param \OCIM\EvenementsBundle\Entity\Inscription $inscription
     * @return Personne
     */
    public function addInscription(\OCIM\EvenementsBundle\Entity\Inscription $inscription)
    {
        $this->inscription[] = $inscription;

        return $this;
    }

    /**
     * Remove inscription
     *
     * @param \OCIM\EvenementsBundle\Entity\Inscription $inscription
     */
    public function removeInscription(\OCIM\EvenementsBundle\Entity\Inscription $inscription)
    {
        $this->inscription->removeElement($inscription);
    }
}
