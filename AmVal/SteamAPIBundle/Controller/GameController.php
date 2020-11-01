<?php

namespace AmVal\SteamAPIBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class GameController extends Controller
{
    /**
     * Gets the details for a game,
     *
     * @param Request $request
     * @param integer $appId
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function detailsAction(Request $request, $appId)
    {
        $steamID64 = $request->getSession()->get('steamID64');
        $gameData = $this->get('doctrine.orm.steam_entity_manager')->getRepository('AmValSteamAPIBundle:Game')->find($appId);
        if (is_null($gameData)) {
            // TODO : the define Game (from User Controller => service)
        }

        $globalAchievementsData = $this->get('doctrine.orm.steam_entity_manager')->getRepository('AmValSteamAPIBundle:Achievement')->findBy(['game' => $appId]);
        if (empty($globalAchievementsData)) {
            $this->get('am_val_steam_api.data_service')->defineGlobalAchievements($appId);
            $globalAchievementsData = $this->get('doctrine.orm.steam_entity_manager')->getRepository('AmValSteamAPIBundle:Achievement')->findBy(['game' => $appId]);
        }
        $globalStatsData = $this->get('doctrine.orm.steam_entity_manager')->getRepository('AmValSteamAPIBundle:Stat')->findBy(['game' => $appId]);
        if (empty($globalStatsData)) {
            $this->get('am_val_steam_api.data_service')->defineGlobalStats($appId);
            $globalStatsData = $this->get('doctrine.orm.steam_entity_manager')->getRepository('AmValSteamAPIBundle:Stat')->findBy(['game' => $appId]);
        }

        // TODO : Test user privacy settings before trying to get info
        $userAchievementsData = $this->get('doctrine.orm.steam_entity_manager')->getRepository('AmValSteamAPIBundle:UserAchievement')->findBy(['game' => $appId, 'user' => $steamID64]);
        if (empty($userAchievementsData)) {
            $this->get('am_val_steam_api.data_service')->defineUserAchievements($appId, $steamID64);
            $userAchievementsData = $this->get('doctrine.orm.steam_entity_manager')->getRepository('AmValSteamAPIBundle:UserAchievement')->findBy(['game' => $appId, 'user' => $steamID64]);
        }
        $userStatsData = $this->get('doctrine.orm.steam_entity_manager')->getRepository('AmValSteamAPIBundle:UserStat')->findBy(['game' => $appId, 'user' => $steamID64]);
        if (empty($userStatsData)) {
            $this->get('am_val_steam_api.data_service')->defineUserStats($appId, $steamID64);
            $userStatsData = $this->get('doctrine.orm.steam_entity_manager')->getRepository('AmValSteamAPIBundle:UserStat')->findBy(['game' => $appId, 'user' => $steamID64]);
        }

        return $this->render(
            'AmValSteamAPIBundle:Game:details.html.twig',
            [
                'game' => $gameData,
                'globalAchievementsData' => $globalAchievementsData,
                'globalStatsData' => $globalStatsData,
                'userAchievementsData' => $userAchievementsData,
                'userStatsData' => $userStatsData
            ]
        );
    }
}
