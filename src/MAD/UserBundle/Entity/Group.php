<?php

namespace MAD\UserBundle\Entity;

use FOS\UserBundle\Model\Group as BaseGroup;
use FOS\UserBundle\Model\GroupInterface;

class Group extends BaseGroup implements GroupInterface
{
    /**
     * @var integer
     */
    protected $id;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $questions;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $users;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->questions = new \Doctrine\Common\Collections\ArrayCollection();
        $this->users = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add questions
     *
     * @param \MAD\ExperienceBundle\Entity\Question $questions
     * @return Group
     */
    public function addQuestion(\MAD\ExperienceBundle\Entity\Question $questions)
    {
        $this->questions[] = $questions;
    
        return $this;
    }

    /**
     * Remove questions
     *
     * @param \MAD\ExperienceBundle\Entity\Question $questions
     */
    public function removeQuestion(\MAD\ExperienceBundle\Entity\Question $questions)
    {
        $this->questions->removeElement($questions);
    }

    /**
     * Get questions
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getQuestions()
    {
        return $this->questions;
    }


    /**
     * Add users
     *
     * @param \MAD\UserBundle\Entity\User $users
     * @return Group
     */
    public function addUser(\MAD\UserBundle\Entity\User $user)
    {
        $this->users->add($user);
    
        return $this;
    }

    /**
     * Remove users
     *
     * @param \MAD\UserBundle\Entity\User $user
     */
    public function removeUser(\MAD\UserBundle\Entity\User $user)
    {
        $this->users->removeElement($user);
    }

    /**
     * Get users
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getUsers()
    {
        return $this->users;
    }
}