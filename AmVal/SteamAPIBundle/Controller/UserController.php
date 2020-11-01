<?php

namespace AmVal\SteamAPIBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;

class UserController extends Controller
{
    /**
     * @param Request $request
     * @return mixed
     */
    public function formAction(Request $request)
    {
        $profiles = $this->get('doctrine.orm.steam_entity_manager')
            ->getRepository('AmValSteamAPIBundle:User')
            ->findAll();

        $profile = null;
        $form = $this->createFormBuilder()
            ->add('steamID', TextType::class, ['label' => 'Steam ID'])
            ->add('Submit', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);
        if ($form->isValid()) {
            $profile = $this->defineProfile($form->getData()['steamID']);
        }

        return $this->render(
            'AmValSteamAPIBundle:User:home.html.twig',
            [
                'form' => $form->createView(),
                'profiles' => $profiles,
                'profile' => $profile
            ]
        );
    }

    /**
     * Gets the profile of the user & initiate the session
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function profileAction()
    {
        $steamID64 = $this->get('session')->get('steamID64');
        $profileData = $this->get('doctrine.orm.steam_entity_manager')
            ->getRepository('AmValSteamAPIBundle:User')
            ->find($steamID64);

        return $this->render(
            'AmValSteamAPIBundle:User:profile.html.twig',
            [
                'profile' => $profileData
            ]
        );
    }

    /**
     * Gets the list of games for the user in session
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function gamesAction()
    {
        $steamID64 = $this->get('session')->get('steamID64');
        $data = $this->get('am_val_steam_api.data_service')->defineGames($steamID64);

        return $this->render(
            'AmValSteamAPIBundle:User:games.html.twig',
            [
                'steamID64' => $steamID64,
                'data' => $data
            ]
        );
    }

    /**
     * TODO : create a service for this
     *
     * @param string $steamID
     * @return \AmVal\SteamAPIBundle\Entity\User|bool
     */
    private function defineProfile($steamID)
    {
        // Getting public Steam profile of the user
        $profileData = $this->get('am_val_steam_api.steam_service')->getPublicProfile($steamID);

        // Checking if the Steam user is in the database
        $profileDBData = null;
        if ($profileData) {
            $this->get('session')->set('steamID64', $profileData['steamID64']);
            $profileDBData = $this->get('doctrine.orm.steam_entity_manager')->getRepository('AmValSteamAPIBundle:User')->find($profileData['steamID64']);
        } else {
            return false;
        }

        // Getting Steam user summary from API & saving infos in database
        if (is_null($profileDBData)) {
            $profileSummary = null;
            if ($profileData) {
                $profileSummary = $this->get('am_val_steam_api.steam_service')->getPlayerSummaries($profileData['steamID64']);
                if ($profileSummary) {
                    $profileData = array_merge($profileData, $profileSummary, ['profileID' => $steamID]);
                }
            }
            return $this->get('am_val_steam_api.storage_service')->storeProfile($profileData);
        }
    }
}
