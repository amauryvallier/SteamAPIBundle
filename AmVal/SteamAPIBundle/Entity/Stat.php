<?php

namespace AmVal\SteamAPIBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Stat
 *
 * @ORM\Table(name="stat")
 * @ORM\Entity
 * @UniqueEntity({"game", "name"})
 */
class Stat
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
     * @var \AmVal\SteamAPIBundle\Entity\Game
     *
     * @ORM\ManyToOne(targetEntity="AmVal\SteamAPIBundle\Entity\Game", inversedBy="achievements")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="game_id", referencedColumnName="app_id")
     * })
     */
    private $game;

    /**
     * @var string $name
     *
     * @ORM\Column(name="name", type="string", nullable=false)
     */
    private $name;

    /**
     * @var string $displayName
     *
     * @ORM\Column(name="display_name", type="string", nullable=false)
     */
    private $displayName;

    /**
     * @var string $defaultValue
     *
     * @ORM\Column(name="default_value", type="string", nullable=true)
     */
    private $defaultValue;

    /**
     * @var float $defaultValue
     *
     * @ORM\Column(name="total", type="float", nullable=true)
     */
    private $total;

    /**
     * Stat constructor.
     *
     * @param Game  $game
     * @param array $statData
     */
    public function __construct(Game $game, $statData)
    {
        // Set related Game
        $this->game = $game;

        // Set datas
        $this->name = $statData['name'];
        // Buggy case : sometimes there are stats with a displayName defined but null (it's for Portal 2, wtf!?)
        if(array_key_exists('displayName', $statData) && !is_null($statData['displayName'])) {
            $this->displayName = $statData['displayName'];
        } else {
            $this->displayName = $statData['name'];
        }
        $this->defaultValue = $statData['defaultvalue'];
        if (array_key_exists('total', $statData)) {
            $this->total = $statData['total'];
        }
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
    public function getDisplayName()
    {
        return $this->displayName;
    }

    /**
     * @param string $displayName
     */
    public function setDisplayName($displayName)
    {
        $this->displayName = $displayName;
    }

    /**
     * @return string
     */
    public function getDefaultValue()
    {
        return $this->defaultValue;
    }

    /**
     * @param string $defaultValue
     */
    public function setDefaultValue($defaultValue)
    {
        $this->defaultValue = $defaultValue;
    }

    /**
     * @return float
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * @param float $total
     */
    public function setTotal($total)
    {
        $this->total = $total;
    }
}
