<?php

define("DIFFICULTY_EASY", 0);
define("DIFFICULTY_MEDIUM", 1);
define("DIFFICULTY_HARD", 2);

class Play
{

    /**
     * Start the game session
     */
    public static function Start()
    {
        $difficulty = filter_input(INPUT_POST, "difficulty", FILTER_VALIDATE_INT);

        if ($difficulty === false || ($difficulty < 0 || $difficulty > 3)) {
            echo json_encode(["status" => false, "message" => "Invalid request!"]);
            return;
        }

        $_SESSION["mode"] = 1;
        $_SESSION["difficulty"] = $difficulty;

        echo json_encode(["status" => true]);
    }

    /**
     * Respond to a move request
     */
    public static function Move()
    {
        $gridData = json_decode(filter_input(INPUT_POST, "grid", FILTER_DEFAULT), true);
        $difficulty = intval($_SESSION["difficulty"]);

        if (!isset($_SESSION["mode"]) || !$gridData) {
            echo json_encode(["status" => false, "message" => "Invalid request!"]);
            return;
        }

        if ($gridData) {
            switch ($difficulty) {
                case DIFFICULTY_HARD: // Find the best course of action, not implemented yet
                case DIFFICULTY_MEDIUM: // Determine the best most by self streaks count
                case DIFFICULTY_EASY: // Choose a random available column
                    $column = Play::moveEasy($gridData);
                    echo json_encode(["status" => true, "column" => $column]);
                    break;
            }
        }
    }
    
    /**
     * Easy Difficulty
     * Choose a random available column
     */
    private static function moveEasy($gridData)
    {
        $availableColumns = [];
        for ($i = 0; $i < sizeof($gridData[0]); $i++) {
            if ($gridData[0][$i] == 0) {
                array_push($availableColumns, $i);
            }
        }

        $rand = random_int(0, sizeof($availableColumns) - 1);
        $column = $availableColumns[$rand];
        return $column;
    }

    /**
     * Medium Difficulty
     * Determine the best move by self horizontal/vertical streaks count
     */
    private static function moveMedium($gridData)
    {
        // Not yet implemented
        return 0;
    }

    /**
     * Hard Difficulty
     * Find the best course of action based on both self and the player,
     * as well as checking diagonally.
     */
    private static function moveHard($gridData)
    {
        // Not yet implemented
        return 0;
    }
}
