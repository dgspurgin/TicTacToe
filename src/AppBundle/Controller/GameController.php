<?php
namespace AppBundle\Controller;

use AppBundle\Entity\Game;
use AppBundle\Entity\Move;

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
	 * @Route("/", "seeGame")
	 * @Method("GET")
	 */
	public function seeAction(Request $request)
    {

		//---------------------------------------------------------
		// Start game if one doesn't exist
		$session = $request->getSession();
		if (is_null($session->get('currentGame'))) {
			static::newGame();
		}

		// Display game
		$data = array();
		$form = $this->createFormBuilder($data);
		for ($r=1; $r <= static::$boardSize; $r++) {
			for($c=1; $c <= static::$boardSize; $c++) {
				$form->add('button_' . $r . '_' . $c, 'submit', array('label' => '   '));
			}
		}
		$form->getForm();
		$form->handleRequest($request);

		if ($form->isValid()) {

			$moves = $session->get('moves');

			for ($r=1; $r <= static::$boardSize; $r++) {
				for($c=1; $c <= static::$boardSize; $c++) {
					if ($form->get('button_' . $r . '_' . $c)->isClicked()) {
						if (is_null($moves[$r][$c])) {
							static::makeMove($r, $c);

							// Remove clicked button
						}
					}
				}
			}
		}
    }


	/**
	 * @Route("/", "engageGame")
	 * @Method("POST")
	 */
	public function engageAction(Request $request) {
		$session = $request->getSession();
		if (is_null($session->get('currentGame'))) {
			// Redirect!
		}

		//



	}


	protected function newGame() {
		//---------------------------------------------------------
		// New game in memory
		$session = $this->get('session');
		$session->remove('currentGame');
		$session->remove('moves');
		$session->remove('pathsClaimed');
		$session->remove('xPlayer');
		$session->remove('oPlayer');
		// count = #Rows + #Cols + 2 Diagonals
		$session->set('pathsAliveCount', PlayController::$boardSize + PlayController::$boardSize + 2 );

		//---------------------------------------------------------
		// New game in db
		$game = new Game();
		$game->setBoardSize(static::$boardSize);
		$game->setXPlayer(static::$xPlayer);
		$game->setOPlayer(static::$oPlayer);
		$em = $this->getDoctrine()->getManager();
		$em->persist($game);
		$em->flush();

		$session->set('currentGame', $game);
		$session->set('xPlayer', $game->getXPlayer());
		$session->set('oPlayer', $game->getOPlayer());
		$session->set('currentPlayer', 1);  // 1 = x, 2 = 0

	}



    protected function makeMove($row, $col, $playerXorO = null)
	{
		$session = $session = $this->get('session');

		//$playerXorO = 1 --> X
		//$playerXorO = 2 --> O
		if (is_null($playerXorO)) {
			$session->get('currentPlayer');
		}

		//---------------------------------------------------------
		// Add move to moves data stored in memory for current game
		$moves = $session->get('moves');
		$moves[$row][$col] = $playerXorO;
		$session->set('moves', $moves);

		$movesCount = $session->get('movesCount');
		$movesCount++;
		$session->set('movesCount', $movesCount);


		//---------------------------------------------------------
		// Update status of paths that are affected by current move
		$pathsClaimed = $session->get('pathsClaimed');
		$pathsAliveCount = $session->get('pathsAliveCount');

		// Row Ownership Check
		if (is_null($pathsClaimed['rows'][$row])) {
			$pathsClaimed['rows'][$row] = $playerXorO;
		}
		elseif ($pathsClaimed['rows'][$row] > 0 && $pathsClaimed['rows'][$row] != $playerXorO) {
			// Other player already claimed row path
			$pathsAliveCount--;
			$pathsClaimed['rows'][$row] = 0;
		}

		// Col Ownership Check
		if (is_null($pathsClaimed['cols'][$col])) {
			$pathsClaimed['cols'][$col] = $playerXorO;
		}
		elseif ($pathsClaimed['cols'][$col] > 0 && $pathsClaimed['cols'][$col] != $playerXorO) {
			// Other player already claimed col path
			$pathsAliveCount--;
			$pathsClaimed['cols'][$col] = 0;
		}

		// Diagonal Ownership Checks

		// If move is on TopLeft to BottomRight diagonal...
		if ($row == $col) {
			if (is_null($pathsClaimed['diags'][1])) {
				$pathsClaimed['rows'][1] = $playerXorO;
			} elseif ($pathsClaimed['diags'][1] > 0  && $pathsClaimed['diags'][1] != $playerXorO) {
				$pathsAliveCount--;
				$pathsClaimed['diags'][1] = 0;
			}
		}

		// If move is on TopRight to BottomLeft diagonal...
		if ($row + $col == (static::$boardSize + 1) ) {
			if (is_null($pathsClaimed['diags'][2])) {
				$pathsClaimed['rows'][2] = $playerXorO;
			} elseif ($pathsClaimed['diags'][2] > 0  && $pathsClaimed['diags'][2] != $playerXorO) {
				$pathsAliveCount--;
				$pathsClaimed['diags'][2] = 0;
			}
		}

		// Save updated path info
		$session->set('pathsClaimed', $pathsClaimed);
		$session->set('pathsAliveCount', $pathsAliveCount);



		//---------------------------------------------------------
		// Determine if current move has triggered tie or win
		$isTie = false;
		$isWinner = false;

		if ($pathsAliveCount <= 0 && static::$stopWhenHopeless == true ) {
			$isTie = true;
		}
		else {

			// Winner?
			if ($pathsClaimed['rows'][$row] == $playerXorO) {
				for ($c = 1; $c <= static::$boardSize; $c++) {
					if ($moves[$row][$c] != $playerXorO) {
						break;
					}
				}
				$isWinner = true;
			} elseif ($pathsClaimed['cols'][$col] == $playerXorO) {
				for ($r = 1; $r <= static::$boardSize; $r++) {
					if ($moves[$r][$col] != $playerXorO) {
						break;
					}
				}
				$isWinner = true;
			} elseif ($pathsClaimed['diags'][1] == $playerXorO) {
				for ($randc = 1; $randc <= static::$boardSize; $randc++) {
					if ($moves[$randc][$randc] != $playerXorO) {
						break;
					}
				}
				$isWinner = true;
			} elseif ($pathsClaimed['diags'][2] == $playerXorO) {
				for ($r = 1; $r <= static::$boardSize; $r++) {
					$c = static::$boardSize + 1 - $r;
					if ($moves[$r][$c] != $playerXorO) {
						break;
					}
				}
				$isWinner = true;
			}

			// Must come after winner check
			if (!$isWinner && $movesCount >= (static::$boardSize * static::$boardSize)) {
				$isTie = true;
			}
		}

		//---------------------------------------------------------
		// Did game just end?
		if (($isTie || $isWinner) && is_null($session->get('winner')) ) {
			if ($isTie) {
				$session->set('winner', 0);
			} elseif ($isWinner) {
				$session->set('winner', $playerXorO);
			}

			// Write moves to db
			$em = $this->getDoctrine()->getManager();
			$xPlayer = $session->get('xPlayer');
			$oPlayer = $session->get('yPlayer');
			$game = $session->get('currentGame');
			$gameId = $game->getGameId();

			for ($r = 1; $r <= static::$boardSize; $r++) {
				for ($c = 1; $c <= static::$boardSize; $c++) {

					$playerId = null;
					if ($moves[$r][$c] == 1) {
						$playerId = $xPlayer;
					} elseif ($moves[$r][$c] == 2) {
						$playerId = $oPlayer;
					}

					$move = new Move();
					$move->setGameId($gameId);
					$move->setPlayerId($playerId);
					$move->setMove($moves[$r][$c]);
					$move->setRow($r);
					$move->setColumn($c);
					$em->persist($move);
					$em->flush();

				}
			}

			// Update game record in db
			$game = $em->getRepository('AppBundle:Game')->find($gameId);
			if (! $game) {
				throw $this->createNotFoundException(
					'No product found for id '. $gameId
				);
			}
			$game->setWinner($session->get('winner'));
			$em->flush();

		}
		else {
			// Game not over so toggle current player after a move
			static::togglePlayer();
		}
    }

	protected function tooglePlayer() {
		$session = $this->get('session');
		$oldCurrentPlayer = $session->get('currentPlayer');
		if ($oldCurrentPlayer == 1 ) {
			$session->set('currentPlayer', 2);
		}
		else {
			$session->set('currentPlayer', 1);
		}
	}


}