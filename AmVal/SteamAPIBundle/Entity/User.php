<?php

namespace AmVal\SteamAPIBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Game
 *
 * @ORM\Table(name="user")
 * @ORM\Entity
 * @UniqueEntity(fields="steam_id_64", message="Steam Profiles are unique.")
 * @UniqueEntity(fields="profile_id", message="Steam Profiles are unique.")
 */
class User
{
    const PRIVACY_PUBLIC = 'public';
    const PRIVACY_FRIENDS = 'friendsonly';
    const PRIVACY_PRIVATE = 'private';

    /**
     * @var integer $steamId64
     *
     * @ORM\Column(name="steam_id_64", type="bigint", nullable=false)
     * @ORM\Id
     */
    private $steamId64;

    /**
     * @var string $profileId
     *
     * @ORM\Column(name="profile_id", type="string", nullable=false)
     */
    private $profileId;

    /**
     * @var string $name
     *
     * @ORM\Column(name="name", type="string", nullable=false)
     */
    private $name;

    /**
     * @var string $privacyState
     *
     * @ORM\Column(name="privacy_state", type="string", nullable=false)
     */
    private $privacyState;

    /**
     * @var \DateTime $creationDate
     *
     * @ORM\Column(name="creation_date", type="datetime", nullable=false)
     */
    private $creationDate;

    /**
     * @var string $profileURL
     *
     * @ORM\Column(name="profile_url", type="string", nullable=false)
     */
    private $profileURL;

    /**
     * @var string $avatarURL
     *
     * @ORM\Column(name="avatar_url", type="string", nullable=false)
     */
    private $avatarURL;

    /**
     * @var \DateTime $lastDataUpdate
     *
     * @ORM\Column(name="last_update", type="datetime", nullable=false)
     */
    private $lastDataUpdate;

    /**
     * Game constructor
     *
     * @param array $profileData
     */
    public function __construct($profileData)
    {
        // Set datas
        $this->steamId64 =    $profileData['steamID64'];
        $this->profileId =    $profileData['profileID'];
        $this->name =         $profileData['personaname'];
        $this->privacyState = $profileData['privacyState'];
        $this->profileURL =   $profileData['profileurl'];
        $this->avatarURL =    $profileData['avatarfull'];
        $this->creationDate = new \DateTime();
        $this->creationDate->setTimestamp($profileData['timecreated']);

        // Setting data update date
        $this->lastDataUpdate = new \DateTime();
    }

    /**
     * @return int
     */
    public function getSteamId64()
    {
        return $this->steamId64;
    }

    /**
     * @param int $steamId64
     */
    public function setSteamId64($steamId64)
    {
        $this->steamId64 = $steamId64;
    }

    /**
     * @return string
     */
    public function getProfileId()
    {
        return $this->profileId;
    }

    /**
     * @param string $profileId
     */
    public function setProfileId($profileId)
    {
        $this->profileId = $profileId;
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
    public function getPrivacyState()
    {
        return $this->privacyState;
    }

    /**
     * @param string $privacyState
     */
    public function setPrivacyState($privacyState)
    {
        $this->privacyState = $privacyState;
    }

    /**
     * @return \DateTime
     */
    public function getCreationDate()
    {
        return $this->creationDate;
    }

    /**
     * @param \DateTime $creationDate
     */
    public function setCreationDate($creationDate)
    {
        $this->creationDate = $creationDate;
    }

    /**
     * @return string
     */
    public function getProfileURL()
    {
        return $this->profileURL;
    }

    /**
     * @param string $profileURL
     */
    public function setProfileURL($profileURL)
    {
        $this->profileURL = $profileURL;
    }

    /**
     * @return string
     */
    public function getAvatarURL()
    {
        return $this->avatarURL;
    }

    /**
     * @param string $avatarURL
     */
    public function setAvatarURL($avatarURL)
    {
        $this->avatarURL = $avatarURL;
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
}
