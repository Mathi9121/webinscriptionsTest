<?php

namespace OCIM\ContactsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TypeStructure
 */
class TypeStructure
{
    /**
     * @var integer
     */
    private $id;

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
     * Set type
     *
     * @param string $type
     * @return TypeStructure
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $structure;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->structure = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add structure
     *
     * @param \OCIM\ContactsBundle\Entity\Structure $structure
     * @return TypeStructure
     */
    public function addStructure(\OCIM\ContactsBundle\Entity\Structure $structure)
    {
        $this->structure[] = $structure;

        return $this;
    }

    /**
     * Remove structure
     *
     * @param \OCIM\ContactsBundle\Entity\Structure $structure
     */
    public function removeStructure(\OCIM\ContactsBundle\Entity\Structure $structure)
    {
        $this->structure->removeElement($structure);
    }

    /**
     * Get structure
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getStructure()
    {
        return $this->structure;
    }
	
	public function __toString(){
		return $this->type;
	}
}
