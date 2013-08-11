<?php

namespace MAD\UserBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;

/**
 * User
 */
class User extends BaseUser
{
    public function __construct()
    {
        parent::__construct();
        // your own logic
    }

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $experiences;

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
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getExperiences()
    {
        return $this->experiences;
    }
}