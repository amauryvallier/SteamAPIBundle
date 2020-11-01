<?php

namespace AmVal\SteamAPIBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Achievement
 *
 * @ORM\Table(name="achievement")
 * @ORM\Entity
 * @UniqueEntity(fields={"game_id", "name"})
 */
class Achievement
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
     * @var string $description
     *
     * @ORM\Column(name="description", type="string", nullable=true)
     */
    private $description;

    /**
     * @var string $defaultValue
     *
     * @ORM\Column(name="default_value", type="string", nullable=true)
     */
    private $defaultValue;

    /**
     * @var bool $hidden
     *
     * @ORM\Column(name="hidden", type="boolean", nullable=true)
     */
    private $hidden;

    /**
     * @var string $iconLockedURL
     *
     * @ORM\Column(name="icon_locked_url", type="string", nullable=true)
     */
    private $iconLockedURL;

    /**
     * @var string $iconUnlockedURL
     *
     * @ORM\Column(name="icon_unlocked_url", type="string", nullable=true)
     */
    private $iconUnlockedURL;

    /**
     * @var float $globalPercentage
     *
     * @ORM\Column(name="global_percentage", type="float", nullable=true)
     */
    private $globalPercentage;

    /**
     * Achievement constructor.
     *
     * @param Game  $game
     * @param array $achievementData
     */
    public function __construct(Game $game, $achievementData)
    {
        // Set related Game
        $this->game = $game;
        // Set datas
        $this->name            = $achievementData['name'];
        $this->displayName     = $achievementData['displayName'];
        if (array_key_exists('description', $achievementData)) {
            $this->description     = $achievementData['description'];
        }
        $this->defaultValue = $achievementData['defaultvalue'];
        $this->hidden          = $achievementData['hidden'];
        $this->iconLockedURL   = $achievementData['icongray'];
        $this->iconUnlockedURL = $achievementData['icon'];
        if (array_key_exists('percent', $achievementData)) {
            $this->globalPercentage = $achievementData['percent'];
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
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
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
     * @return bool
     */
    public function isHidden()
    {
        return $this->hidden;
    }

    /**
     * @param bool $hidden
     */
    public function setHidden($hidden)
    {
        $this->hidden = $hidden;
    }

    /**
     * @return string
     */
    public function getIconLockedURL()
    {
        return $this->iconLockedURL;
    }

    /**
     * @param string $iconLockedURL
     */
    public function setIconLockedURL($iconLockedURL)
    {
        $this->iconLockedURL = $iconLockedURL;
    }

    /**
     * @return string
     */
    public function getIconUnlockedURL()
    {
        return $this->iconUnlockedURL;
    }

    /**
     * @param string $iconUnlockedURL
     */
    public function setIconUnlockedURL($iconUnlockedURL)
    {
        $this->iconUnlockedURL = $iconUnlockedURL;
    }
}
