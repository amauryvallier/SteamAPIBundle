<?php

namespace AmVal\SteamAPIBundle\Twig\Extension;

class GlobalsExtension extends \Twig_Extension implements \Twig_Extension_GlobalsInterface
{
    protected $steamAPIStaticURL;

    public function __construct($steamAPIStaticURL)
    {
        $this->steamAPIStaticURL = $steamAPIStaticURL;
    }

    public function getGlobals()
    {
        return array(
            'steam_api_static_url' => $this->steamAPIStaticURL
        );
    }

    public function getName()
    {
        return 'globals';
    }
}