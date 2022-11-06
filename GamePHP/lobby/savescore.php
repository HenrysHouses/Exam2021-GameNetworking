<?php
// Initialize the session
session_start();

// Include config.php
require_once("../include/config.php");

// Process data from form
  if ($_SERVER["REQUEST_METHOD"] == "POST") 
  {
	$sql = "INSERT INTO Leaderboard (lapTime, UserID, replayData, userName) values (:laptime, :userId, :ghost, :username)";

	$userId = $_SESSION["id"]; //userid
	$userScore = trim($_POST["time"]);
	$positions = trim($_POST["ghostInput"]);

	if ($stmt = $conn->prepare($sql)) 
	{
		$stmt->bindParam(":userId", $param_userId, PDO::PARAM_INT);
		$stmt->bindParam(":laptime", $param_score, PDO::PARAM_INT);
		$stmt->bindParam(":ghost", $param_positions, PDO::PARAM_STR);
		$stmt->bindParam(":username", $param_username, PDO::PARAM_STR);

		// Set parameters
		$param_userId = intval($userId);
		$param_score = intval($userScore);
		$param_positions = $positions;
		$param_username = $_SESSION["username"];

		// Attempt to insert data
		if ($stmt->execute()) {
			echo ("0"); // Ok
		} else {
			echo ("-1"); // Error
		}
	} else {
		echo ("-3");
	}
}  else {
	echo ("-2"); // Error
 }
unset($stmt);
unset($conn);
?>