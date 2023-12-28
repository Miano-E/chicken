<?php

	include("config.php");
	session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $myusername = mysqli_real_escape_string($con, $_POST['username']);
    $mypassword = mysqli_real_escape_string($con, $_POST['password']);

    if (empty($myusername)) {
        header("Location: login.php?error=Username required");
        exit();
    } elseif (empty($mypassword)) {
        header("Location: login.php?error=Password required");
        exit();
    } else {
        // Construct SQL query
        $sql = "SELECT * FROM kuku_table WHERE username = '$myusername'";
        $results = mysqli_query($con, $sql);
        
        if (!$results) {
            // Handle query execution error
            header("Location: login.php?error=Database error");
            exit();
        }
        
        $row = mysqli_fetch_assoc($results);
        
        if ($row) {
            // Verify the password
            $hashedPassword = $row['password'];
            if (password_verify($mypassword, $hashedPassword)) {
                // Password matches, set session variables
                $_SESSION['username'] = $row['username'];
               /* $_SESSION['firstName'] = $row['firstname'];
                $_SESSION['lastName'] = $row['lastName'];*/
                header("Location: index.php");
                exit();
            } else {
                // Incorrect password
                header("Location: login.php?error=Incorrect password");
                exit();
            }
        } else {
            // User with the provided username doesn't exist
            header("Location: login.php?error=Incorrect username or password");
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Login</title>
	<link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="login.css">
	<link rel="stylesheet" href="main.css">
</head>
<body>
	<div class="container">
		<div class="feature flip">
				<div class="content">
					<h2>Access Your Chicken Farming Hub</h2>
					<p class="description">
                        Welcome back to your dedicated space for chicken expertise. 
                        Log in to explore premium content and personalized resources. 
                        Your journey continues here.
					</p>
				</div>

			<form action="<?php echo $_SERVER['PHP_SELF'];?>" method = "post">
				<div class = "login_container">
					<h3>Login</h3>
					<?php if (isset($_GET['error'])) { ?>
							<p class = "error"><?php echo $_GET['error']; ?></p>
					<?php }?>

					<label for = "username">Username</label>
					<input type = "text" name = "username">
					
					<label for = "password">Password</label>
					<input type = "password" name = "password" id = "myInput">
                    
					<input type = "checkbox" onclick = "myFunction()">Show Password
					
					<button type = "submit" name = "submitbtn" class = "submitbtn">Login</button>
					<p class = "check">Don't have an account? <a href = "contact.php">Signup</p>
					</div>
			</form>
		</div>

		<script>
			function myFunction(){
				var a = document.getElementById("myInput");
				if(a.type === "password"){
					a.type = "text";
				}else{
					a.type = "password";
				}
			}
		</script>

</body>
</html>