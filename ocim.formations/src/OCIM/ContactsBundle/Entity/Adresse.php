<?php

namespace OCIM\ContactsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Adresse
 */
class Adresse
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $nomStructure;

    /**
     * @var string
     */
    private $adresse;

    /**
     * @var string
     */
    private $adresseComplement;

    /**
     * @var string
     */
    private $cP;

    /**
     * @var string
     */
    private $ville;

    /**
     * @var string
     */
    private $pays;


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
     * Set nomStructure
     *
     * @param string $nomStructure
     * @return Adresse
     */
    public function setNomStructure($nomStructure)
    {
        $this->nomStructure = $nomStructure;

        return $this;
    }

    /**
     * Get nomStructure
     *
     * @return string
     */
    public function getNomStructure()
    {
        return $this->nomStructure;
    }

    /**
     * Set adresse
     *
     * @param string $adresse
     * @return Adresse
     */
    public function setAdresse($adresse)
    {
        $this->adresse = $adresse;

        return $this;
    }

    /**
     * Get adresse
     *
     * @return string
     */
    public function getAdresse()
    {
        return $this->adresse;
    }

    /**
     * Set adresseComplement
     *
     * @param string $adresseComplement
     * @return Adresse
     */
    public function setAdresseComplement($adresseComplement)
    {
        $this->adresseComplement = $adresseComplement;

        return $this;
    }

    /**
     * Get adresseComplement
     *
     * @return string
     */
    public function getAdresseComplement()
    {
        return $this->adresseComplement;
    }

    /**
     * Set cP
     *
     * @param string $cP
     * @return Adresse
     */
    public function setCP($cP)
    {
        $this->cP = $cP;

        return $this;
    }

    /**
     * Get cP
     *
     * @return string
     */
    public function getCP()
    {
        return $this->cP;
    }

    /**
     * Set ville
     *
     * @param string $ville
     * @return Adresse
     */
    public function setVille($ville)
    {
        $this->ville = mb_strtoupper($ville);

        return $this;
    }

    /**
     * Get ville
     *
     * @return string
     */
    public function getVille()
    {
        return $this->ville;
    }

    /**
     * Set pays
     *
     * @param string $pays
     * @return Adresse
     */
    public function setPays($pays)
    {
        $this->pays = $pays;

        return $this;
    }

    /**
     * Get pays
     *
     * @return string
     */
    public function getPays()
    {
        return $this->pays;
    }

    /**
     * @var \OCIM\ContactsBundle\Entity\TypeStructure
     */
    private $type;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $tags;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->tags = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set type
     *
     * @param \OCIM\ContactsBundle\Entity\TypeStructure $type
     * @return Adresse
     */
    public function setType(\OCIM\ContactsBundle\Entity\TypeStructure $type = null)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return \OCIM\ContactsBundle\Entity\TypeStructure
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Add tags
     *
     * @param \OCIM\ContactsBundle\Entity\TagStructure $tag
     * @return Adresse
     */
    public function addTag(\OCIM\ContactsBundle\Entity\TagStructure $tag)
    {
        $this->tags[] = $tag;

        return $this;
    }

    /**
     * Remove tags
     *
     * @param \OCIM\ContactsBundle\Entity\TagStructure $tag
     */
    public function removeTag(\OCIM\ContactsBundle\Entity\TagStructure $tag)
    {
        $this->tags->removeElement($tag);
    }

    /**
     * Get tags
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTags()
    {
        return $this->tags;
    }

	public function setTags($tags)
    {
        $this->tags = $tags;

		return $this;
    }

}
