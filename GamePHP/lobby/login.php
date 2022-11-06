<?php
// Initialize the session
session_start();
 
// Check if the user is already logged in, if yes then redirect him to welcome page
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
	echo ("4");
	exit;
}
 
// Include config file
require_once "../include/config.php";
 
// Define variables and initialize with empty values
$username = $password = "";
$username_err = $password_err = $returnMsg = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Check if username is empty
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter username.";
    } else{
        $username = trim($_POST["username"]);
    }
    
    // Check if password is empty
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter your password.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validate credentials
    if(empty($username_err) && empty($password_err)){
        // Prepare a select statement
        $sql = "SELECT UserID, UserName, UserPassword FROM RegisteredUsers WHERE UserName = :username";
        
        if($stmt = $conn->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(":username", $param_username, PDO::PARAM_STR);
            
            // Set parameters
            $param_username = trim($_POST["username"]);
            
            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // Check if username exists, if yes then verify password
                if($stmt->rowCount() == 1){
                    if($row = $stmt->fetch()){
                        $id = $row["UserID"];
                        $username = $row["UserName"];
                        $hashed_password = $row["UserPassword"];
                        if(password_verify($password, $hashed_password)){
                            // Password is correct, so start a new session
                            // session_start();
                            
                            // Store data in session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username;  
                            
                            // Redirect user to welcome page
                            $returnMsg = "0;" . $id;
                        } else{
                            // Display an error message if password is not valid
                            $password_err = "The password you entered was not valid.";
							$returnMsg = "1";
                        }
                    }
                } else{
                    // Display an error message if username doesn't exist
                    $username_err = "No account found with that username.";
					$returnMsg = "2";
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
				$returnMsg = "3";
            }
        }
        
        // Close statement
        unset($stmt);
    }
    
    // Close connection
    unset($conn);
	echo $returnMsg;
} else {
	echo("3");
} 
?>