<?php

namespace App\EventListener;

use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\SecurityBundle\Security;

class AdminAccessListener
{
    public function __construct(
        private Security $security,
        private UrlGeneratorInterface $urlGenerator
    ) {}

    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();
        
        // Check if this is an admin route (except login)
        if (str_starts_with($request->getPathInfo(), '/admin') && 
            !str_starts_with($request->getPathInfo(), '/admin/login')) {
            
            $user = $this->security->getUser();
            
            // If no user or user doesn't have ROLE_ADMIN, redirect to admin login
            if (!$user || !in_array('ROLE_ADMIN', $user->getRoles())) {
                $response = new RedirectResponse($this->urlGenerator->generate('admin_login'));
                $event->setResponse($response);
                return;
            }
        }
    }
}
