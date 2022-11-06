<?php
    // Attempt to connect to database
    require_once("../include/config.php");

    $sql = "SELECT lapTime, userName, scoreId FROM Leaderboard ORDER BY lapTime DESC LIMIT 10";

    // Select all scores and corresponding user names from the users and highscore tables
    // Order the list descending
    $result = $conn->query($sql);

    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        echo ($row["userName"] . ":" . $row["lapTime"] . ":" . $row["scoreId"] . ";");
    }
    
    unset($conn);

?>
