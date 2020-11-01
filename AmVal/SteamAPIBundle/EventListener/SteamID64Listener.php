<?php

namespace AmVal\SteamAPIBundle\EventListener;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\HttpKernel;
use Symfony\Component\Routing\RouterInterface;

/**
 * Class SteamID64Listener
 * For each action, there is a redirection to the home page if we have no steam ID in session
 *
 * @package AmVal\SteamAPIBundle\EventListener
 */
class SteamID64Listener
{
    /**
     * @var RouterInterface $router
     */
    private $router;

    /**
     * SteamID64Listener constructor.
     *
     * @param RouterInterface $router
     */
    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    /**
     * @param GetResponseEvent $event
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        if (HttpKernel::MASTER_REQUEST != $event->getRequestType()) {
            // don't do anything if it's not the master request
            return;
        }

        $request = $event->getRequest();

        if ($request->get('_route') == 'am_val_steam_api_form' || !strstr($request->get('_controller'), 'SteamAPIBundle')) {
            return;
        }

        if (!$request->getSession()->has('steamID64')) {
            $event->setResponse(new RedirectResponse($this->router->generate('am_val_steam_api_form')));
        }
    }
}
