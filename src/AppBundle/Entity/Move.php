<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="move")
 */
class Move
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Id
     */
    protected $moveId;


    /**
     * @ORM\ManyToOne(targetEntity="Game")
     * @ORM\JoinColumn(name="gameId", referencedColumnName="gameId")
     * @ORM\Column(type="integer")
     */
    protected $gameId;


    /**
     * @ORM\ManyToOne(targetEntity="Player")
     * @ORM\JoinColumn(name="playerId", referencedColumnName="playerId")
     * @ORM\Column(type="integer")
     */
    protected $playerId;


    /**
     * @ORM\Column(type="integer")
     */
    protected $move;  // 1 = x, 2 = 0

    /**
     * @ORM\Column(type="integer")
     */
    protected $row;

    /**
     * @ORM\Column(type="integer")
     */
    protected $column;


    /**
     * Get moveId
     *
     * @return integer
     */
    public function getMoveId()
    {
        return $this->moveId;
    }

    /**
     * Set gameId
     *
     * @param integer $gameId
     *
     * @return Move
     */
    public function setGameId($gameId)
    {
        $this->gameId = $gameId;

        return $this;
    }

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
     * Set playerId
     *
     * @param integer $playerId
     *
     * @return Move
     */
    public function setPlayerId($playerId)
    {
        $this->playerId = $playerId;

        return $this;
    }

    /**
     * Get playerId
     *
     * @return integer
     */
    public function getPlayerId()
    {
        return $this->playerId;
    }

    /**
     * Set move
     *
     * @param integer $move
     *
     * @return Move
     */
    public function setMove($move)
    {
        $this->move = $move;

        return $this;
    }

    /**
     * Get move
     *
     * @return integer
     */
    public function getMove()
    {
        return $this->move;
    }

    /**
     * Set row
     *
     * @param integer $row
     *
     * @return Move
     */
    public function setRow($row)
    {
        $this->row = $row;

        return $this;
    }

    /**
     * Get row
     *
     * @return integer
     */
    public function getRow()
    {
        return $this->row;
    }

    /**
     * Set column
     *
     * @param integer $column
     *
     * @return Move
     */
    public function setColumn($column)
    {
        $this->column = $column;

        return $this;
    }

    /**
     * Get column
     *
     * @return integer
     */
    public function getColumn()
    {
        return $this->column;
    }
}
