<?php

namespace MAD\ExperienceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Experience
 */
class Experience
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $account;

    /**
     * @var boolean
     */
    private $sharedWithResearcher = true;

    /**
     * @var boolean
     */
    private $sharedWithAll = false;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var \MAD\UserBundle\Entity\User
     */
    private $user;

    /**
     * @var \MAD\ExperienceBundle\Entity\Question
     */
    private $question;


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
     * Set account
     *
     * @param string $account
     * @return Experience
     */
    public function setAccount($account)
    {
        $this->account = $account;
    
        return $this;
    }

    /**
     * Get account
     *
     * @return string 
     */
    public function getAccount()
    {
        return $this->account;
    }

    /**
     * Set sharedWithResearcher
     *
     * @param boolean $sharedWithResearcher
     * @return Experience
     */
    public function setSharedWithResearcher($sharedWithResearcher)
    {
        $this->sharedWithResearcher = $sharedWithResearcher;
    
        return $this;
    }

    /**
     * Get sharedWithResearcher
     *
     * @return boolean 
     */
    public function getSharedWithResearcher()
    {
        return $this->sharedWithResearcher;
    }

    /**
     * Set sharedWithAll
     *
     * @param boolean $sharedWithAll
     * @return Experience
     */
    public function setSharedWithAll($sharedWithAll)
    {
        $this->sharedWithAll = $sharedWithAll;
    
        return $this;
    }

    /**
     * Get sharedWithAll
     *
     * @return boolean 
     */
    public function getSharedWithAll()
    {
        return $this->sharedWithAll;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Experience
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    
        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set user
     *
     * @param \MAD\UserBundle\Entity\User $user
     * @return Experience
     */
    public function setUser(\MAD\UserBundle\Entity\User $user)
    {
        $this->user = $user;
    
        return $this;
    }

    /**
     * Get user
     *
     * @return \MAD\UserBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set question
     *
     * @param \MAD\ExperienceBundle\Entity\Question $question
     * @return Experience
     */
    public function setQuestion(Question $question = null)
    {
        $this->question = $question;
    
        return $this;
    }

    /**
     * Get question
     *
     * @return \MAD\ExperienceBundle\Entity\Question
     */
    public function getQuestion()
    {
        return $this->question;
    }
}
