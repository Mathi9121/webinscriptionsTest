<?php

namespace OCIM\EvenementsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Convention
 */
class Convention
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $numero;

    /**
     * @var \DateTime
     */
    private $edition;

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
     * Set numero
     *
     * @param string $numero
     * @return Convention
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;

        return $this;
    }

    /**
     * Get numero
     *
     * @return string
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * Set edition
     *
     * @param \DateTime $edition
     * @return Convention
     */
    public function setEdition($edition)
    {
        $this->edition = $edition;

        return $this;
    }

    /**
     * Get edition
     *
     * @return \DateTime
     */
    public function getEdition()
    {
        return $this->edition;
    }


    /**
     * @var \OCIM\EvenementsBundle\Entity\Inscription
     */
    private $inscription;


    /**
     * Set inscription
     *
     * @param \OCIM\EvenementsBundle\Entity\Inscription $inscription
     * @return Convention
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

	public function __toString(){
		return $this->numero;
	}
    /**
     * @var \DateTime
     */
    private $etape1;

    /**
     * @var \DateTime
     */
    private $etape2;

    /**
     * @var \DateTime
     */
    private $etape3;


    /**
     * Set etape1
     *
     * @param \DateTime $etape1
     * @return Convention
     */
    public function setEtape1($etape1)
    {
        $this->etape1 = $etape1;

        return $this;
    }

    /**
     * Get etape1
     *
     * @return \DateTime
     */
    public function getEtape1()
    {
        return $this->etape1;
    }

    /**
     * Set etape2
     *
     * @param \DateTime $etape2
     * @return Convention
     */
    public function setEtape2($etape2)
    {
        $this->etape2 = $etape2;

        return $this;
    }

    /**
     * Get etape2
     *
     * @return \DateTime
     */
    public function getEtape2()
    {
        return $this->etape2;
    }

    /**
     * Set etape3
     *
     * @param \DateTime $etape3
     * @return Convention
     */
    public function setEtape3($etape3)
    {
        $this->etape3 = $etape3;

        return $this;
    }

    /**
     * Get etape3
     *
     * @return \DateTime
     */
    public function getEtape3()
    {
        return $this->etape3;
    }
    /**
     * @var \DateTime
     */
    private $etape4;


    /**
     * Set etape4
     *
     * @param \DateTime $etape4
     * @return Convention
     */
    public function setEtape4($etape4)
    {
        $this->etape4 = $etape4;

        return $this;
    }

    /**
     * Get etape4
     *
     * @return \DateTime
     */
    public function getEtape4()
    {
        return $this->etape4;
    }

    public function getNumeroToString(){
      if((!is_null($this->getNUmero()))&&(!is_null($this->getEdition()))){
        return $this->getEdition()->format('Y/m/').$this->getNumero();
      }
      else return false;
    }
}
