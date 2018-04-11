<?php

namespace OCIM\ContactsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;


/**
 * TagStructure
 */
class TagStructure
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $tag;



	public function __construct() {
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
     * Set tag
     *
     * @param string $tag
     * @return TagStructure
     */
    public function setTag($tag)
    {
        $this->tag = $tag;

        return $this;
    }

    /**
     * Get tag
     *
     * @return string
     */
    public function getTag()
    {
        return $this->tag;
    }



	public function __toString(){
		return $this->tag;
	}
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $structures;


    /**
     * Add structures
     *
     * @param \OCIM\ContactsBundle\Entity\Adresse $structures
     * @return TagStructure
     */
    public function addStructure(\OCIM\ContactsBundle\Entity\Adresse $structures)
    {
        $this->structures[] = $structures;

        return $this;
    }

    /**
     * Remove structures
     *
     * @param \OCIM\ContactsBundle\Entity\Adresse $structures
     */
    public function removeStructure(\OCIM\ContactsBundle\Entity\Adresse $structures)
    {
        $this->structures->removeElement($structures);
    }

    /**
     * Get structures
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getStructures()
    {
        return $this->structures;
    }
}
