<?php

namespace MAD\UserBundle\EventListener;


use FOS\UserBundle\FOSUserEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Router;

class FirstPasswordChangeSubscriber implements EventSubscriberInterface
{
    /**
     * @var \Symfony\Component\Routing\Router
     */
    protected $router;

    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    public static function getSubscribedEvents()
    {
        return array(FOSUserEvents::CHANGE_PASSWORD_SUCCESS, 'onChangePassword');
    }

    public function onChangePassword($event)
    {
        /** @var \MAD\UserBundle\Entity\User $user */
        $user = $event->getForm()->getData();

        if ($user->getPasswordChanged()) {
            return;
        }

        $user->setPasswordChanged(true);

        $event->setResponse(new RedirectResponse($this->router->generate('mad_user_edit_picture')));
    }
}