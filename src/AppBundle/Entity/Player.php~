<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

 /**
  * @ORM\Entity(repositoryClass="AppBundle\Repository\PlayerRepository")
  * @ORM\Table(name="player")
 */
class Player
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
	 * @ORM\Id
     */
    protected $playerID;


    /**
     * @ORM\Column(type="string", length=100)
     */
	protected $playerName;

    /**
     * Set playerID
     *
     * @param integer $playerID
     *
     * @return Player
     */
    public function setPlayerID($playerID)
    {
        $this->playerID = $playerID;

        return $this;
    }

    /**
     * Get playerID
     *
     * @return integer
     */
    public function getPlayerID()
    {
        return $this->playerID;
    }

    /**
     * Set playerName
     *
     * @param string $playerName
     *
     * @return Player
     */
    public function setPlayerName($playerName)
    {
        $this->playerName = $playerName;

        return $this;
    }

    /**
     * Get playerName
     *
     * @return string
     */
    public function getPlayerName()
    {
        return $this->playerName;
    }
}
