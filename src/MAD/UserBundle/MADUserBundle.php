<?php

namespace MAD\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class MADUserBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
