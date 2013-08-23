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
}