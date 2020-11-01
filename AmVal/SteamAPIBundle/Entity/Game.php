<?php

namespace AmVal\SteamAPIBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Game
 *
 * @ORM\Table(name="game")
 * @ORM\Entity(repositoryClass="AmVal\SteamAPIBundle\Repository\GameRepository")
 * @UniqueEntity(fields="app_id", message="Steam Games are unique.")
 */
class Game
{
    /**
     * @var integer $appId
     *
     * @ORM\Column(name="app_id", type="integer", nullable=false)
     * @ORM\Id
     */
    private $appId;

    /**
     * @var string $name
     *
     * @ORM\Column(name="name", type="string", nullable=false)
     */
    private $name;

    /**
     * @var integer $playtime
     *
     * @ORM\Column(name="playtime", type="integer", nullable=false)
     */
    private $playtime;

    /**
     * @var string $iconURL
     *
     * @ORM\Column(name="icon_url", type="string", nullable=true)
     */
    private $iconURL;

    /**
     * @var string $logoURL
     *
     * @ORM\Column(name="logo_url", type="string", nullable=true)
     */
    private $logoURL;

    /**
     * @var \DateTime $lastDataUpdate
     *
     * @ORM\Column(name="last_update", type="datetime", nullable=false)
     */
    private $lastDataUpdate;

    /**
     * @var \AmVal\SteamAPIBundle\Entity\Achievement[] $achievements
     *
     * @ORM\OneToMany(targetEntity="AmVal\SteamAPIBundle\Entity\Achievement", mappedBy="game")
     */
    private $achievements;

    /**
     * @var \AmVal\SteamAPIBundle\Entity\Stat[] $stats
     *
     * @ORM\OneToMany(targetEntity="AmVal\SteamAPIBundle\Entity\Stat", mappedBy="game")
     */
    private $stats;

    /**
     * Game constructor
     *
     * @param array $gameData
     */
    public function __construct($gameData)
    {
        // Set datas
        $this->appId =    $gameData['appid'];
        $this->name =     $gameData['name'];
        $this->playtime = $gameData['playtime_forever'];
        $this->iconURL =  $gameData['img_icon_url'];
        $this->logoURL =  $gameData['img_logo_url'];

        // Setting data update date
        $this->lastDataUpdate = new \DateTime();

        // Set relations
        $this->achievements = new ArrayCollection();
        $this->stats        = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getAppId()
    {
        return $this->appId;
    }

    /**
     * @param int $appId
     */
    public function setAppId($appId)
    {
        $this->appId = $appId;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return int
     */
    public function getPlaytime()
    {
        return $this->playtime;
    }

    /**
     * @param int $playtime
     */
    public function setPlaytime($playtime)
    {
        $this->playtime = $playtime;
    }

    /**
     * @return string
     */
    public function getIconURL()
    {
        return $this->iconURL;
    }

    /**
     * @param string $iconURL
     */
    public function setIconURL($iconURL)
    {
        $this->iconURL = $iconURL;
    }

    /**
     * @return string
     */
    public function getLogoURL()
    {
        return $this->logoURL;
    }

    /**
     * @param string $logoURL
     */
    public function setLogoURL($logoURL)
    {
        $this->logoURL = $logoURL;
    }

    /**
     * @return \DateTime
     */
    public function getLastDataUpdate()
    {
        return $this->lastDataUpdate;
    }

    /**
     * @param \DateTime $lastDataUpdate
     */
    public function setLastDataUpdate($lastDataUpdate)
    {
        $this->lastDataUpdate = $lastDataUpdate;
    }

    /**
     * @return \AmVal\SteamAPIBundle\Entity\Achievement[]
     */
    public function getAchievements()
    {
        return $this->achievements;
    }

    /**
     * @param \AmVal\SteamAPIBundle\Entity\Achievement[] $achievements
     */
    public function setAchievements($achievements)
    {
        $this->achievements = $achievements;
    }

    /**
     * Add achievement
     *
     * @param \AmVal\SteamAPIBundle\Entity\Achievement $achievement
     *
     * @return Game
     */
    public function addAchievement(\AmVal\SteamAPIBundle\Entity\Achievement $achievement)
    {
        $this->achievements[] = $achievement;

        return $this;
    }

    /**
     * Remove achievement
     *
     * @param \AmVal\SteamAPIBundle\Entity\Achievement $achievement
     */
    public function removeAchievement(\AmVal\SteamAPIBundle\Entity\Achievement $achievement)
    {
        $this->achievements->removeElement($achievement);
    }

    /**
     * @return \AmVal\SteamAPIBundle\Entity\Stat[]
     */
    public function getStats()
    {
        return $this->stats;
    }

    /**
     * @param \AmVal\SteamAPIBundle\Entity\Stat[] $stats
     */
    public function setStats($stats)
    {
        $this->stats = $stats;
    }

    /**
     * Add stat
     *
     * @param \AmVal\SteamAPIBundle\Entity\Stat $stat
     *
     * @return Game
     */
    public function addStat(\AmVal\SteamAPIBundle\Entity\Stat $stat)
    {
        $this->stats[] = $stat;

        return $this;
    }

    /**
     * Remove stat
     *
     * @param \AmVal\SteamAPIBundle\Entity\Stat $stat
     */
    public function removeStat(\AmVal\SteamAPIBundle\Entity\Stat $stat)
    {
        $this->stats->removeElement($stat);
    }
}
