<?php

namespace MAD\UserBundle\Security\Authentication\Handler;

use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Router;

class FirstPasswordChangeHandler implements AuthenticationSuccessHandlerInterface
{

    protected $router;
    protected $security;

    public function __construct(Router $router, SecurityContext $security)
    {
        $this->router = $router;
        $this->security = $security;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token)
    {
        /** @var \MAD\UserBundle\Entity\User $user */
        $user = $this->security->getToken()->getUser();

        if (!$user->getPasswordChanged()) {
            $response = new RedirectResponse($this->router->generate('fos_user_change_password'));
        } elseif (null === $user->getPicture()) {
            $response = new RedirectResponse($this->router->generate('mad_user_edit_picture'));
        } elseif ($request->headers->get('referer')) {
            // redirect the user to where they were before the login process begun.
            $referer_url = $request->headers->get('referer');

            $response = new RedirectResponse($referer_url);
        } else {
            $response = new RedirectResponse($this->router->generate('mad_experience_homepage'));
        }

        return $response;
    }

}