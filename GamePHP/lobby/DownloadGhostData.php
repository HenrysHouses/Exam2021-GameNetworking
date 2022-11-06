<?php
// Initialize the session
session_start();

// Include config.php
require_once("../include/config.php");

// Process data from form
// if ($_SERVER["REQUEST_METHOD"] == "POST") 
// {
	// $sql = "SELECT Leaderboard (replayData) WHERE scoreId = :id";
	$sql = "SELECT replayData FROM Leaderboard WHERE scoreId = :id";

	$_scoreID = trim($_POST["_scoreID"]);


	if ($stmt = $conn->prepare($sql)) 
	{
		$stmt->bindParam(":id", $param_scoreID, PDO::PARAM_INT);
		$param_scoreID = (int)$_scoreID;

		if($stmt->execute())
		{
			$count = $stmt->rowCount();
			if($count > 0)
			{
				if($row = $stmt->fetch())
				{
					echo $row["replayData"];
				}
			}
			else
				echo "no data found";
		}
		else
			echo "-1";
	}
	else
		echo "-2";
	// 	// $stmt->bindParam(":id", $param_id, PDO::PARAM_INT);

	// 	// Set parameters
	// 	// $param_id = intval($_scoreID);

	// 	// Attempt to insert data
	// 	if ($stmt->execute()) {
	// 		if($row = $)
	// 		echo ("0"); // Ok
	// 	} else {
	// 		echo ("-1"); // Error
	// 	}
	// }
	// else 
	// {
	// 	echo ("-3"); // Error
	// }
// }  
// else 
// {
// 	echo ("-2"); // Error
// }
unset($stmt);
unset($conn);
?>