<?php

namespace AmVal\SteamAPIBundle\Service;

use AmVal\SteamAPIBundle\Entity\Achievement;
use AmVal\SteamAPIBundle\Entity\Game;
use AmVal\SteamAPIBundle\Entity\Stat;
use AmVal\SteamAPIBundle\Entity\User;
use AmVal\SteamAPIBundle\Entity\UserAchievement;
use AmVal\SteamAPIBundle\Entity\UserStat;
use Doctrine\ORM\EntityManager;
use Monolog\Logger;

class DataStorageService
{
    /**
     * @var EntityManager $entityManager
     */
    protected $entityManager;

    /**
     * @var Logger $logger
     */
    protected $logger;

    /**
     * DataStorageService constructor.
     *
     * @param EntityManager $entityManager
     * @param Logger        $logger
     */
    public function __construct(EntityManager $entityManager, Logger $logger)
    {
        $this->entityManager = $entityManager;
        $this->logger = $logger;
    }

    public function storeProfile($profileData)
    {
        $profile = new User($profileData);
        $this->entityManager->persist($profile);
        $this->entityManager->flush($profile);

        return $profile;
    }

    public function storeGame($gameData)
    {
        $game = new Game($gameData);
        $this->entityManager->persist($game);
        $this->entityManager->flush($game);

        return $game;
    }

    public function storeAchievement($gameId, $achievementData)
    {
        $game = $this->entityManager->getRepository('AmValSteamAPIBundle:Game')->find($gameId);
        $achievement = new Achievement($game, $achievementData);
        $this->entityManager->persist($achievement);
        $this->entityManager->flush($achievement);

        return $achievement;
    }

    public function storeUserAchievement($achievementId, $gameId, $userId, $achievementData)
    {
        $achievement = $this->entityManager->getRepository('AmValSteamAPIBundle:Achievement')->find($achievementId);
        $game = $this->entityManager->getRepository('AmValSteamAPIBundle:Game')->find($gameId);
        $user = $this->entityManager->getRepository('AmValSteamAPIBundle:User')->find($userId);
        $userAchievement = new UserAchievement($achievement, $game, $user, $achievementData);
        $this->entityManager->persist($userAchievement);
        $this->entityManager->flush($userAchievement);

        return $userAchievement;
    }

    public function storeStat($gameId, $statData)
    {
        $game = $this->entityManager->getRepository('AmValSteamAPIBundle:Game')->find($gameId);
        $achievement = new Stat($game, $statData);
        $this->entityManager->persist($achievement);
        $this->entityManager->flush($achievement);

        return $achievement;
    }

    public function storeUserStat($statId, $gameId, $userId, $statData)
    {
        $stat = $this->entityManager->getRepository('AmValSteamAPIBundle:Stat')->find($statId);
        $game = $this->entityManager->getRepository('AmValSteamAPIBundle:Game')->find($gameId);
        $user = $this->entityManager->getRepository('AmValSteamAPIBundle:User')->find($userId);
        $userStat = new UserStat($stat, $game, $user, $statData);
        $this->entityManager->persist($userStat);
        $this->entityManager->flush($userStat);

        return $userStat;
    }

    public function getLastGameDataCollectionDate($appId)
    {
        $game = $this->entityManager->getRepository('AmValSteamAPIBundle:Game')->find($appId);
        return $game->getLastDataUpdate();
    }

    public function updateGameDataCollectionDate($appId)
    {
        $game = $this->entityManager->getRepository('AmValSteamAPIBundle:Game')->find($appId);
        $game->setLastDataUpdate(new \DateTime());
        $this->entityManager->persist($game);
        $this->entityManager->flush($game);
    }

    public function getLastUserDataCollectionDate($steamID64)
    {
        $user = $this->entityManager->getRepository('AmValSteamAPIBundle:User')->find($steamID64);
        return $user->getLastDataUpdate();
    }

    public function getUserPrivacy($steamID64)
    {
        $user = $this->entityManager->getRepository('AmValSteamAPIBundle:User')->find($steamID64);
        return $user->getPrivacyState();
    }

    public function updateUserDataCollectionDate($steamID64)
    {
        $user = $this->entityManager->getRepository('AmValSteamAPIBundle:User')->find($steamID64);
        $user->setLastDataUpdate(new \DateTime());
        $this->entityManager->persist($user);
        $this->entityManager->flush($user);
    }
}