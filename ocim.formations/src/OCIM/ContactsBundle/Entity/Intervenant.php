<?php

namespace OCIM\ContactsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * Intervenant
 */
class Intervenant extends Personne
{
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $evenements;


    /**
     * Add evenements
     *
     * @param \OCIM\EvenementsBundle\Entity\Evenement $evenements
     * @return Intervenant
     */
    public function addEvenement(\OCIM\EvenementsBundle\Entity\Evenement $evenements)
    {
        $this->evenements[] = $evenements;

        return $this;
    }

    /**
     * Remove evenements
     *
     * @param \OCIM\EvenementsBundle\Entity\Evenement $evenements
     */
    public function removeEvenement(\OCIM\EvenementsBundle\Entity\Evenement $evenements)
    {
        $this->evenements->removeElement($evenements);
    }

    /**
     * Get evenements
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEvenements()
    {
        return $this->evenements;
    }
}
