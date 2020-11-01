<?php

namespace AmVal\SteamAPIBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * UserAchievement
 *
 * @ORM\Table(name="user_achievement")
 * @ORM\Entity
 * @UniqueEntity({"game", "user", "name"})
 */
class UserAchievement
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var Achievement
     *
     * @ORM\ManyToOne(targetEntity="Achievement")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="achievement_id", referencedColumnName="id", onDelete="CASCADE")
     * })
     */
    private $achievement;

    /**
     * @var Game
     *
     * @ORM\ManyToOne(targetEntity="Game")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="game_id", referencedColumnName="app_id")
     * })
     */
    private $game;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="AmVal\SteamAPIBundle\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="steam_id_64")
     * })
     */
    private $user;

    /**
     * @var string $name
     *
     * @ORM\Column(name="name", type="string", nullable=false)
     */
    private $name;

    /**
     * @var boolean $achieved
     *
     * @ORM\Column(name="achieved", type="boolean", nullable=false)
     */
    private $achieved;

    /**
     * @var \DateTime $unlockTime
     *
     * @ORM\Column(name="unlock_time", type="datetime", nullable=true)
     */
    private $unlockTime;

    /**
     * Achievement constructor.
     *
     * @param Achievement $achievement
     * @param Game        $game
     * @param User        $user
     * @param array       $achievementData
     */
    public function __construct(Achievement $achievement, Game $game, User $user, $achievementData)
    {
        // Set related Achievement
        $this->achievement = $achievement;
        // Set related Game
        $this->game = $game;
        // Set related User
        $this->user = $user;
        // Set datas
        $this->name       = $achievementData['apiname'];
        $this->achieved   = $achievementData['achieved'];
        $this->unlockTime = new \DateTime();
        $this->unlockTime->setTimestamp($achievementData['unlocktime']);
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return Achievement
     */
    public function getAchievement()
    {
        return $this->achievement;
    }

    /**
     * @param Achievement $achievement
     */
    public function setAchievement($achievement)
    {
        $this->achievement = $achievement;
    }

    /**
     * @return Game
     */
    public function getGame()
    {
        return $this->game;
    }

    /**
     * @param Game $game
     */
    public function setGame($game)
    {
        $this->game = $game;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser($user)
    {
        $this->user = $user;
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
     * @return bool
     */
    public function isAchieved()
    {
        return $this->achieved;
    }

    /**
     * @param bool $achieved
     */
    public function setAchieved($achieved)
    {
        $this->achieved = $achieved;
    }

    /**
     * @return \DateTime
     */
    public function getUnlockTime()
    {
        return $this->unlockTime;
    }

    /**
     * @param \DateTime $unlockTime
     */
    public function setUnlockTime($unlockTime)
    {
        $this->unlockTime = $unlockTime;
    }
}
