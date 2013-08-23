<?php

namespace MAD\UserBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * User
 */
class User extends BaseUser
{
    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    private $experiences;


    public function __construct()
    {
        parent::__construct();

        $this->experiences = new ArrayCollection();
    }

    /**
     * Add experiences
     *
     * @param \MAD\ExperienceBundle\Entity\Experience $experiences
     * @return User
     */
    public function addExperience(\MAD\ExperienceBundle\Entity\Experience $experiences)
    {
        $this->experiences[] = $experiences;
    
        return $this;
    }

    /**
     * Remove experiences
     *
     * @param \MAD\ExperienceBundle\Entity\Experience $experiences
     */
    public function removeExperience(\MAD\ExperienceBundle\Entity\Experience $experiences)
    {
        $this->experiences->removeElement($experiences);
    }

    /**
     * Get experiences
     *
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getExperiences()
    {
        return $this->experiences;
    }
}