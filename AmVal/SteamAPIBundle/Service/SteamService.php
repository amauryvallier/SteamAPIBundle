<?php

namespace AmVal\SteamAPIBundle\Service;

use Monolog\Logger;

/**
 * Class SteamService
 * This class provides the access to the Steam API with functions directly based on the ones of the API
 *
 * @package AmVal\SteamAPIBundle\Service
 */
class SteamService
{
    /**
     * @var SteamUtility $steamUtility
     */
    protected $steamUtility;

    /**
     * @var Logger $logger
     */
    protected $logger;

    /**
     * SteamService constructor.
     *
     * @param SteamUtility $steamUtility
     * @param Logger       $logger
     */
    public function __construct(SteamUtility $steamUtility, Logger $logger)
    {
        $this->steamUtility = $steamUtility;
        $this->logger = $logger;
    }

    /**
     * Gets a public Steam profile
     *
     * @param int $steamID
     * @return bool|mixed
     */
    public function getPublicProfile($steamID)
    {
        if (is_numeric($steamID)) {
            $profileUrl = "http://steamcommunity.com/profiles/$steamID";
        } else {
            $profileUrl = "http://steamcommunity.com/id/" . strtolower($steamID);
        }

        if (!is_null($profileUrl)) {
            try {
                // This URL is only available with xml formatting
                $profileContent = $this->steamUtility->fetchURL($profileUrl, [SteamUtility::RESP_FORMAT_XML => 1]);
                if ($profileContent) {
                    $xmlContent = simplexml_load_string($profileContent);
                    $jsonContent = json_encode($xmlContent);
                    return json_decode($jsonContent,true);
                } else {
                    $this->logger->addNotice("No result for this call : " . $profileUrl);
                }
            } catch (\Exception $e) {
                $this->logger->addWarning($e->getMessage());
            }
        }
        return false;
    }

    /** ISteamUserStats scope => TODO : create child class and make interfaces with variables for scope/method/version */

    /**
     * From API doc:
     * Returns basic profile information for a list of 64-bit Steam IDs.
     *
     * @param int $steamID64
     * @return array|null
     */
    public function getPlayerSummaries($steamID64)
    {
        $data = $this->steamUtility->fetchXmlDataFromUrl(
            $this->steamUtility->buildBaseUrlFromMethod('ISteamUser', 'GetPlayerSummaries', 'v0002'),
            [
                'steamids' => $steamID64
            ]
        );
        if (!is_null($data)) {
            return $data['players']['player'];
        }
        return null;
    }

    /** ISteamUserStats scope => TODO : create child class and make interfaces with variables for scope/method/version */

    /**
     * From API doc:
     * GetOwnedGames returns a list of games a player owns along with some playtime information, if the profile is
     * publicly visible. Private, friends-only, and other privacy settings are not supported unless you are asking for
     * your own personal details (ie the WebAPI key you are using is linked to the steamid you are requesting).
     *
     * @param int $steamID64
     * @return array|null
     */
    public function getOwnedGames($steamID64)
    {
        $data = $this->steamUtility->fetchJsonDataFromUrl(
            $this->steamUtility->buildBaseUrlFromMethod('IPlayerService', 'GetOwnedGames', 'v0001'),
            [
                'steamid' => $steamID64,
                'include_appinfo' => true
            ]
        );
        if (!is_null($data)) {
            return $data['response'];
        }
        return null;
    }

    /** ISteamUserStats scope => TODO : create child class and make interfaces with variables for scope/method/version */

    /**
     * From API doc:
     * GetSchemaForGame returns gamename, gameversion and availablegamestats(achievements and stats).
     *
     * @param int $appId
     * @return array|null
     */
    public function getSchemaForGame($appId)
    {
        $data = $this->steamUtility->fetchJsonDataFromUrl(
            $this->steamUtility->buildBaseUrlFromMethod('ISteamUserStats', 'GetSchemaForGame', 'v2'),
            [
                'appid' => $appId,
            ]
        );
        if (!is_null($data)) {
            return $data['game'];
        }
        return null;
    }

    /**
     * From API doc:
     * Returns a list of achievements for this user by app id
     *
     * @param int $steamID64
     * @param int $appId
     * @return array|null
     */
    public function getUserStatsForGame($steamID64, $appId)
    {
        $data = $this->steamUtility->fetchJsonDataFromUrl(
            $this->steamUtility->buildBaseUrlFromMethod('ISteamUserStats', 'GetUserStatsForGame', 'v0002'),
            [
                'steamid' => $steamID64,
                'appid' => $appId,
            ]
        );
        if (!is_null($data)) {
            return $data['playerstats'];
        }
        return null;
    }

    /**
     * From API doc:
     * Returns a list of achievements for this user by app id
     *
     * @param int $steamID64
     * @param int $appId
     * @return array|null
     */
    public function getPlayerAchievements($steamID64, $appId)
    {
        $data = $this->steamUtility->fetchJsonDataFromUrl(
            $this->steamUtility->buildBaseUrlFromMethod('ISteamUserStats', 'GetPlayerAchievements', 'v0001'),
            [
                'steamid' => $steamID64,
                'appid' => $appId,
            ]
        );
        if (!is_null($data)) {
            return $data['playerstats'];
        }
        return null;
    }

    /**
     * From API doc:
     * Returns on global achievements overview of a specific game in percentages.
     *
     * @param int $gameId
     * @return array|null
     */
    public function getGlobalAchievementPercentagesForApp($gameId)
    {
        $data = $this->steamUtility->fetchJsonDataFromUrl(
            $this->steamUtility->buildBaseUrlFromMethod('ISteamUserStats', 'GetGlobalAchievementPercentagesForApp', 'v0002'),
            [
                'gameid' => $gameId,
            ]
        );
        if (!is_null($data)) {
            return $data['achievementpercentages']['achievements'];
        }
        return null;
    }

    /**
     * @param integer $appId
     * @param array   $stats
     * @return array|null
     */
    public function getGlobalStatsForGame($appId, $stats)
    {
        // Case of GetGlobalStatsForGame API method
        $namedParams = [];
        $paramCount = 0;
        foreach ($stats as $value) {
            $namedParams['name[' . $paramCount . ']'] = $value['name'];
            $paramCount++;
        }

        $data = $this->steamUtility->fetchJsonDataFromUrl(
            $this->steamUtility->buildBaseUrlFromMethod('ISteamUserStats', 'GetGlobalStatsForGame', 'v0001'),
            array_merge(
                [
                    'appid' => $appId,
                    'count' => count($stats)
                ],
                $namedParams
            )
        );
        if (!is_null($data) && $data["response"]['result'] == 1) {
            return $data["response"]['globalstats'];
        }
        return null;
    }
}