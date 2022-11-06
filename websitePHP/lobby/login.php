<?php
session_start();
require_once("../include/config.php");

// check if user is already logged in
if (isset($_SESSION["loggedin"]) && isset($_SESSION["loggedin"]) === true) {
    header("location: ../index.php");
    exit;
}

// define variables and initialize
$username = $password = "";
$username_err = $password_err = "";

// Processing form data when submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
// Check if username is empty
    if (empty(trim($_POST["username"]))) {
        $username_err = "Please enter a username";
    } else {
        $username = trim($_POST["username"]);
    }
    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter a password";
    } else {
        $password = trim($_POST["password"]);
    }   
    
    // Validate credentials and try to log in
    if (empty($username_err) && empty($password_err)) {
        // Prepare an sql statement
        $sql = "SELECT userID, userName, userPassword FROM users21 WHERE userName = :username";
        
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bindParam(":username", $param_username, PDO::PARAM_STR);
            $param_username = $username;
            
            if ($stmt->execute()) {
                if ($stmt->rowCount() == 1) {
                    if ($row = $stmt->fetch()) {
                        $id = $row["userID"];
                        $username = $row["userName"];
                        $hashed_password = $row["userPassword"];
                        if (password_verify($password, $hashed_password)){
                            // Password verified - start a new session
                            session_start();
                            // Store data in sesssion variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username;
                            
							unset($stmt);
							$sql = "UPDATE users21 SET userLastActive = NOW() WHERE userID = $id";		
							
							 if ($stmt = $conn->prepare($sql)) {
								$stmt->execute();
							 }
							
                            // Redirect to welcome page
                            header("location: ../index.php");
                            
                        } else {
                            // Display an error message if password was not valid
                            $password_err = "The password was not valid";
                        }
                    }
                }
            }
        }
    }
}
?>

<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>Log in</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootswatch@4.5.2/dist/minty/bootstrap.min.css" integrity="sha384-H4X+4tKc7b8s4GoMrylmy2ssQYpDHoqzPa9aKXbDwPoPUA3Ra8PA5dGzijN+ePnH" crossorigin="anonymous">
<meta name="viewport" content="width=device-width, initial-scale=1">
</head>

<body>
<div class="text-center container">
    No account yet? <a href="./registeruser.php">Sign up now!</a><br />
    <p>Please fill in your credentials:</p>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <label>User name:</label><br />
        <input type="text" name="username" value="<?php echo $username; ?>"><br />
        <p><?php echo $username_err; ?></p>
        <label>Password:</label><br />
        <input type="password" name="password"><br />
        <p><?php echo $password_err; ?></p>
        <input type="submit" value="Login"><br />
    </form>
</div>
</body>
</html>
