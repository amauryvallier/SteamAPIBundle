<?php

namespace AmVal\SteamAPIBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * UserStat
 *
 * @ORM\Table(name="user_stat")
 * @ORM\Entity
 * @UniqueEntity({"game", "user", "name"})
 */
class UserStat
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
     * @ORM\ManyToOne(targetEntity="Stat")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="stat_id", referencedColumnName="id", onDelete="CASCADE")
     * })
     */
    private $stat;

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
     * @ORM\ManyToOne(targetEntity="User")
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
     * @var string $value
     *
     * @ORM\Column(name="value", type="float", nullable=false)
     */
    private $value;

    /**
     * Stat constructor.
     *
     * @param Stat  $stat
     * @param Game  $game
     * @param User  $user
     * @param array $statData
     */
    public function __construct(Stat $stat, Game $game, User $user, $statData)
    {
        // Set related Stat
        $this->stat = $stat;
        // Set related Game
        $this->game = $game;
        // Set related User
        $this->user = $user;
        // Set datas
        $this->name = $statData['name'];
        $this->value = $statData['value'];
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
    public function getStat()
    {
        return $this->stat;
    }

    /**
     * @param Achievement $stat
     */
    public function setStat($stat)
    {
        $this->stat = $stat;
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
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param string $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }
}
