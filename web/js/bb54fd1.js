
var playerMarks = ["", "X", "O"];
var gameId = 1;
var winningPlayer = null;
var currentPlayer = 1;
var moves = [];
var pathsClaimed = {};
var pathsWon = {};

var boardSize = 3;
var pathsCount = boardSize + boardSize + 2;  // #row paths + #col paths + 2 diagonals
var pathsAliveCount = pathsCount;
var shouldStopWhenHomeless = true;
var isGameOver = false;
var isWinner = false;
var isTie = false;



init();


    $("#btnNewGame").hide();
    $("#message").text("Current Player: ". currentPlayer);

    $("#btnNewGame").click(newGame);

    $(".xo-button").click(function() {
        if (isGameOver === true) {
            // No more moves allowed if game is over
            return;
        }

        var buttonId = $(this).attr('id');
        var selectedRow = $(this).attr('row');
        var selectedCol = $(this).attr('col');
        var buttonText = playerMarks[currentPlayer];

        // Change button to text
        $('#' + buttonId).replaceWith(buttonText);

        // Make the move and update game state
        makeMove(selectedRow, selectedCol);

        if (isGameOver === true) {
            // json call to app to persist the final state of game

            // update interface
            $("#btnNewGame").show();
            var message = "Tie Game";
            if (winner) {
                message = "Winner: Player " + playerMarks[currentPlayer];
            }
            $("#message").text(message);

        }
        else {
            togglePlayer();

        }

    });

function init() {
    pathsClaimed = {};
    pathsClaimed.rows = [];
    pathsClaimed.cols = [];
    pathsClaimed.diags = [];
    pathsClaimed.diags[1] = null;
    pathsClaimed.diags[2] = null;

    pathsWon = {};
    pathsWon.rows = null;
    pathsWon.cols = null;
    pathsWon.diags = [];
    pathsWon.diags[1] = null;
    pathsWon.diags[2] = null;

    moves = [];
    var r = boardSize, c = boardSize;
    for (r=1; r<=boardSize; r++) {
        for (c=1; c<=boardSize; c++) {
            moves[r] = [];
            moves[r][c] = null;
        }
    }



}

function newGame() {
    location.reload();
}

function makeMove(row, col) {
    row = parseInt(row);
    col = parseInt(col);

    // Add move to moves data stored in memory for current game
    moves[row][col] = currentPlayer;
    var isPossibleRowWin = false;
    var isPossibleColWin = false;
    var isPossibleDiagonalWin = [];
    isPossibleDiagonalWin[1] = false;
    isPossibleDiagonalWin[2] = false;

    //---------------------------------------------------------
    // Update status of paths that are affected by current move

    // pathsClaimed will have value of
    // undefined = not claimed
    // 1 = X owns
    // 2 = O owns
    // 0 = Both players have claim on path. Don't check in future.

    // Row ownership check
    if (! pathsClaimed['rows'][row]) {
        pathsClaimed['rows'][row] = currentPlayer;
    }
    else if (pathsClaimed['rows'][row] > 0) {
        if (pathsClaimed['rows'][row] != currentPlayer) {
            pathsAliveCount--;
            pathsClaimed['rows'][row] = -1;
        }
        else {
            isPossibleRowWin = true;
        }
    }

    // Col ownership check
    if (! pathsClaimed['cols'][col]) {
        pathsClaimed['cols'][col] = currentPlayer;
    }
    else if (pathsClaimed['cols'][col] > 0) {
        if (pathsClaimed['cols'][col] != currentPlayer) {
            pathsAliveCount--;
            pathsClaimed['cols'][col] = -1;
        }
        else {
            isPossibleColWin = true;
        }
    }

    // Diagonal ownership check
    // If move in on TopLeft to BottomRight diagonal

    if (row === col) {
        if (! pathsClaimed['diags'][1]) {
            alert('claim diag 1');
            pathsClaimed['diags'][1] = currentPlayer;
        }
        else if (pathsClaimed['diags'][1] > 0) {
            if (pathsClaimed['diags'][1] != currentPlayer) {
                pathsAliveCount--;
                pathsClaimed['diags'][1] = -1;
            }
            else {
                isPossibleDiagonalWin[1] = true;
            }
        }
    }

    // If move is on TopRight to BottomLeft diagonal
    if (row + col === (boardSize + 1) ) {
        if (! pathsClaimed['diags'][2]) {
            alert('claim diag 2');
            pathsClaimed['diags'][2] = currentPlayer;
        }
        else if (pathsClaimed['diags'][2] > 0)
            if (pathsClaimed['diags'][2] != currentPlayer) {
                pathsAliveCount--;
                pathsClaimed['diags'][2] = -1;
            }
            else {
                isPossibleDiagonalWin[2] = true;
            }

    }

    // left = row + col;
    // right = boardSize + 1;
    // alert(left + ' row+col = boarsize+1 ' +  right);

    // alert(JSON.stringify(moves, null, 4));
    alert(pathsAliveCount + '/' + pathsCount + ' still alive ');
    alert(JSON.stringify(pathsClaimed, null, 4));


    //---------------------------------------------------------
    // Determine if current move has triggered win or tie
    isWinner = false;
    isTie = false;

    // Check for tie: part 1
    if (pathsAliveCount <= 0 && shouldStopWhenHomeless === true ) {
        isTie = true;
    }
    else {

        // Check for winner

        // Row winner?
        if (isPossibleRowWin) {

            winnerCheck: {
                for (c = 1; c <= boardSize; c++) {
                    if (moves[row][c] != currentPlayer) {
                        break winnerCheck;
                    }
                }
                isWinner = true;
                pathsWon['row'] = row;
            }
        }

        // Col winner?
        if (isPossibleColWin) {
            winnerCheck: {
                for (r = 1; r <= boardSize; r++) {
                    if (moves[r][col] != currentPlayer) {
                        break winnerCheck;
                    }
                }
                isWinner = true;
                pathsWon['col'] = col;
            }
        }

        // Diagonal 1 winner?
        if (isPossibleDiagonalWin[1]) {
            winnerCheck: {
                for (rowcol = 1; rowcol <= boardSize; rowcol++) {
                    if (moves[rowcol][rowcol] != currentPlayer) {
                        break winnerCheck;
                    }
                }
                isWinner = true;
                pathsWon['diagonal'][1] = true;
            }
        }

        // Diagonal 2 winner?
        if (isPossibleDiagonalWin[2]) {
            winnerCheck: {
                for (r = 1; r <= boardSize; r++) {
                    c = boardSize + 1 - r;
                    if (moves[r][c] != currentPlayer) {
                        break winnerCheck;
                    }
                }
                isWinner = true;
                pathsWon['diagonal'][2] = true;
            }
        }

        // Check for tie: part 2
        // (must come after winner check)
        if (isWinner === false && moves.length >= (boardSize * boardSize)) {
            isTie = true;
        }
    }

    //---------------------------------------------------------
    // Did game just end?
    if (isTie === true || isWinner === true) {
        isGameOver = true;

        if (isTie === true) {
            winner = 0;
        }
        else if (isWinner) {
            winner = currentPlayer;
        }
    }
}

function togglePlayer() {
    if (currentPlayer === 1 ) {
        currentPlayer = 2;
    }
    else {
        currentPlayer = 1;
    }
}

