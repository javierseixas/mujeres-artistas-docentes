<?php

namespace MAD\CommentBundle\Event;

use MAD\UserBundle\Entity\User;
use Symfony\Component\EventDispatcher\Event;

class CommentEvent extends Event
{
    /**
     * @var \MAD\UserBundle\Entity\User
     */
    protected $experienceAuthor;

    public function __construct(User $experienceAuthor)
    {
        $this->experienceAuthor = $experienceAuthor;
    }

    public function getExperienceAuthor()
    {
        return $this->experienceAuthor;
    }
}