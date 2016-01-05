<?php
namespace AppBundle\Controller;

use AppBundle\Entity\Game;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class PlayController extends Controller
{
	// Default x/o players
	static $xPlayer = 1;
	static $oPlayer = 2;
	static $boardSize = 3;
	static $stopWhenHopeless = true;


	/**
	 * @Route("/", name="play")
	 * @Method("GET")
	 */
	public function playAction(Request $request)
    {
		return $this->render('board.html.twig');
    }


	/**
	 * @Route("/report", name="report")
	 * @Method("POST")
	 */
	public function reportAction(Request $request)
	{

		$winner = null;


		if ($winner) {
			$this->persistCompletedGame($winner);

			// success
		}
		else {
			// failure
		}

	}


	protected function persistCompletedGame($winner = null) {

		//---------------------------------------------------------
		// New game in db
		$game = new Game();
		$game->setBoardSize(static::$boardSize);
		$game->setXPlayer(static::$xPlayer);
		$game->setOPlayer(static::$oPlayer);
		$game->setWinner($winner);
		$em = $this->getDoctrine()->getManager();
		$em->persist($game);
		$em->flush();

	}





}