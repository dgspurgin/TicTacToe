<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="game")
 */
class Game
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Id
     */

    protected $gameId;

    /**
     * @ORM\Column(type="integer")
     */
    protected $boardSize;

    /**
     * @ORM\ManyToOne(targetEntity="Player")
     * @ORM\JoinColumn(name="xPlayer", referencedColumnName="playerId")
     * @ORM\Column(type="integer")
     */
    protected $xPlayer;

    /**
     * @ORM\ManyToOne(targetEntity="Player")
     * @ORM\JoinColumn(name="oPlayer", referencedColumnName="playerId")
     * @ORM\Column(type="integer")
     */
    protected $oPlayer;

    /**
     * @ORM\ManyToOne(targetEntity="Player")
     * @ORM\JoinColumn(name="winner", referencedColumnName="playerId")
     * @ORM\Column(type="integer", options={"default" = null})
     */

    protected $winner;      // 0 = tie, 1 = xPlayer, 2 = oPlayer, null = no winner yet




    /**
     * Get gameId
     *
     * @return integer
     */
    public function getGameId()
    {
        return $this->gameId;
    }

    /**
     * Set boardSize
     *
     * @param integer $boardSize
     *
     * @return Game
     */
    public function setBoardSize($boardSize)
    {
        $this->boardSize = $boardSize;

        return $this;
    }

    /**
     * Get boardSize
     *
     * @return integer
     */
    public function getBoardSize()
    {
        return $this->boardSize;
    }

    /**
     * Set xPlayer
     *
     * @param integer $xPlayer
     *
     * @return Game
     */
    public function setXPlayer($xPlayer)
    {
        $this->xPlayer = $xPlayer;

        return $this;
    }

    /**
     * Get xPlayer
     *
     * @return integer
     */
    public function getXPlayer()
    {
        return $this->xPlayer;
    }

    /**
     * Set oPlayer
     *
     * @param integer $oPlayer
     *
     * @return Game
     */
    public function setOPlayer($oPlayer)
    {
        $this->oPlayer = $oPlayer;

        return $this;
    }

    /**
     * Get oPlayer
     *
     * @return integer
     */
    public function getOPlayer()
    {
        return $this->oPlayer;
    }

    /**
     * Set winner
     *
     * @param integer $winner
     *
     * @return Game
     */
    public function setWinner($winner)
    {
        $this->winner = $winner;

        return $this;
    }

    /**
     * Get winner
     *
     * @return integer
     */
    public function getWinner()
    {
        return $this->winner;
    }
}
