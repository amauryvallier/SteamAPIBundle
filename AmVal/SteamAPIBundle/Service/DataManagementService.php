<?php

namespace AmVal\SteamAPIBundle\Service;

use Doctrine\ORM\EntityManager;
use Monolog\Logger;

class DataManagementService
{
    /**
     * @var EntityManager $entityManager
     */
    protected $entityManager;

    /**
     * @var SteamService $steamService
     */
    protected $steamService;

    /**
     * @var DataStorageService $storageService
     */
    protected $storageService;

    /**
     * @var Logger $logger
     */
    protected $logger;

    /**
     * DataManagementService constructor.
     *
     * @param EntityManager      $entityManager
     * @param SteamService       $steamService
     * @param DataStorageService $storageService
     * @param Logger             $logger
     */
    public function __construct(
        EntityManager $entityManager,
        SteamService $steamService,
        DataStorageService $storageService,
        Logger $logger)
    {
        $this->entityManager = $entityManager;
        $this->steamService = $steamService;
        $this->storageService = $storageService;
        $this->logger = $logger;
    }

    /**
     * @param string $steamID64
     * @return array|null
     */
    public function defineGames($steamID64)
    {
        $data = $this->steamService->getOwnedGames($steamID64);

        foreach ($data['games'] as $gameData) {
            $dbGame = $this->entityManager
                ->getRepository('AmValSteamAPIBundle:Game')
                ->find($gameData['appid']);
            if (is_null($dbGame)) {
                $this->storageService->storeGame($gameData);
            }
        }
        return $data;
    }

    /**
     * @param integer $appId
     */
    public function defineGlobalAchievements($appId)
    {
        $gameSchema = $this->steamService->getSchemaForGame($appId);
        $globalAchievementsData = $this->steamService->getGlobalAchievementPercentagesForApp($appId);
        $storedAchievements = $this->entityManager->getRepository('AmValSteamAPIBundle:Achievement')->findBy(['game' => $appId]);

        if (array_key_exists('availableGameStats', $gameSchema) && array_key_exists('achievements', $gameSchema['availableGameStats'])) {
            $achievements = $gameSchema['availableGameStats']['achievements'];

            // We aggregate the data from the two API methods
            foreach ($achievements as $key => $achievement) {
                $achievements[$achievement['name']] = $achievement;
                unset($achievements[$key]);
            }
            foreach ($globalAchievementsData as $key => $achievement) {
                $globalAchievementsData[$achievement['name']] = $achievement;
                unset($globalAchievementsData[$key]);
                // Buggy case : sometimes there are in the global achievements elements that are not in the availableGameStats (it's for Counter Strike, wtf!?)
                if (array_key_exists($achievement['name'], $achievements)) {
                    $achievements[$achievement['name']]['percent'] = $globalAchievementsData[$achievement['name']]['percent'];
                }
            }

            // We store only if there are more achievements than already stored in DB
            // TODO : make it a replace & a flag to force update
            if (count($storedAchievements) < count($achievements)) {
                foreach ($achievements as $key => $achievement) {
                    $this->storageService->storeAchievement($appId, $achievement);
                }
            }
        }
    }

    /**
     * @param integer $appId
     */
    public function defineGlobalStats($appId)
    {
        $gameSchema = $this->steamService->getSchemaForGame($appId);
        $storedStats = $this->entityManager->getRepository('AmValSteamAPIBundle:Stat')->findBy(['game' => $appId]);

        $globalStatsIndexes = null;
        if (array_key_exists('availableGameStats', $gameSchema) && array_key_exists('stats', $gameSchema['availableGameStats'])) {
            $stats = $gameSchema['availableGameStats']['stats'];
            foreach ($stats as $key => $stat) {
                // We want only the global aggregated stats
                if (substr($stat['name'], 0, 7) === 'global.') {
                    $globalStatsIndexes[$stat['name']] = $stat;
                }
                $stats[$stat['name']] = $stat;
                unset($stats[$key]);
            }
            if (!is_null($globalStatsIndexes) && !empty($globalStatsIndexes)) {
                $globalStatsData = $this->steamService->getGlobalStatsForGame($appId, $globalStatsIndexes);
                foreach ($globalStatsData as $key => $stat) {
                    $stats[$key]['total'] = $globalStatsData[$key]['total'];
                }
            }
            // We store only if there are more stats than already stored in DB
            // TODO : make it a replace & a flag to force update
            if (count($storedStats) < count($stats)) {
                foreach ($stats as $stat) {
                    $this->storageService->storeStat($appId, $stat);
                }
            }
        }
    }

    /**
     * @param integer $appId
     * @param integer $steamID64
     */
    public function defineUserAchievements($appId, $steamID64)
    {
        $userAchievementsData = $this->steamService->getPlayerAchievements($steamID64, $appId);
        $storedUserAchievements = $this->entityManager->getRepository('AmValSteamAPIBundle:UserAchievement')->findBy(['game' => $appId, 'user' => $steamID64]);

        if (!is_null($userAchievementsData) && array_key_exists('achievements', $userAchievementsData)) {
            $achievements = $userAchievementsData['achievements'];

            // We store only if there are more achievements than already stored in DB
            // TODO : make it a replace & a flag to force update
            if (count($storedUserAchievements) < count($achievements)) {
                foreach ($achievements as $key => $userAchievement) {
                    $globalAchievement = $this->entityManager
                        ->getRepository('AmValSteamAPIBundle:Achievement')
                        ->findOneBy(['game' => $appId, 'name' => $userAchievement['apiname']]);
                    if (!is_null($globalAchievement)) {
                        $this->storageService->storeUserAchievement($globalAchievement->getId(), $appId, $steamID64, $userAchievement);
                    }
                }
            }
        }
    }

    /**
     * @param integer $appId
     * @param integer $steamID64
     */
    public function defineUserStats($appId, $steamID64)
    {
        $userStatsData = $this->steamService->getUserStatsForGame($steamID64, $appId);
        $storedUserStats = $this->entityManager->getRepository('AmValSteamAPIBundle:UserStat')->findBy(['game' => $appId, 'user' => $steamID64]);

        if (!is_null($userStatsData) && array_key_exists('stats', $userStatsData)) {
            $stats = $userStatsData['stats'];

            // We store only if there are more stats than already stored in DB
            // TODO : make it a replace & a flag to force update
            if (count($storedUserStats) < count($stats)) {
                foreach ($stats as $key => $userStat) {
                    $globalStat = $this->entityManager
                        ->getRepository('AmValSteamAPIBundle:Stat')
                        ->findOneBy(['game' => $appId, 'name' => $userStat['name']]);
                    if (!is_null($globalStat)) {
                        $this->storageService->storeUserStat($globalStat->getId(), $appId, $steamID64, $userStat);
                    }
                }
            }
        }
    }
}