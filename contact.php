<?php

	include_once("config.php");

	$username = "";
	$firstName = "";
	$lastName = "";
	$email = "";
	$password = "";
	$confirmPassword = "";

	//Retrieve data from the form
    if(isset($_POST["submit"])){
        $username = trim($_POST['username']);
		$firstName = trim($_POST['firstName']);
		$lastName = trim($_POST['lastName']);
		$email = $_POST['email'];
		$password = $_POST['password'];
		$confirmPassword = $_POST['confirmPassword'];

		$errors = array();
		
        if(empty($username)) {
           $errors['username'] = "Username required"; 
        }else if(strlen($username) < 4) {
			$errors['username'] = "Username must have atleast 4 characters";
		}else if(preg_match("#[0-9]+#", $username)) {
			$errors['username'] = "Username should not contain numbers";
		}
		
		
		if(empty($firstName)) {
			$errors['firstName'] = "First Name required";
		}
		
		if(empty($lastName)) {
			$errors['lastName'] = "Last Name required.";
		}
	
		
		if(empty($email)) {
			$errors['email'] = "Email required";
		}else if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$errors['email'] = "Enter a valid email";
		}
		
		
		if(empty($password)) {
			$errors['password'] = "Enter Password";
		}else if(strlen($password) < 8) {
			$errors['password'] = "Password must be at least 8 characters";
		}else if(!preg_match("#[0-9]+#", $password)){
			$errors['password'] = "Password must contain numbers";
		}
		
		
		if(empty($confirmPassword)) {
			$errors['confirmPassword'] = "Confirm Password";
		}else if($confirmPassword != $password) {
			$errors['confirmPassword'] = "Password Mismatch!";
		}
		
		// Check for duplicate username
    	if (!empty($username)) {
			$stmt = $con->prepare("SELECT username FROM kuku_table WHERE username = ?");
			$stmt->bind_param("s", $username);
			$stmt->execute();
			$stmt->store_result();
        
			if ($stmt->num_rows > 0) {
				$errors['username'] = "Username already exists";
			}
    	}

    	// Check for duplicate email
    	if (!empty($email)) {
			$stmt = $con->prepare("SELECT email FROM kuku_table WHERE email = ?");
			$stmt->bind_param("s", $email);
			$stmt->execute();
			$stmt->store_result();
        
			if ($stmt->num_rows > 0) {
				$errors['email'] = "Email already exists";
			}
    	}
		
			// Password Hashing
			$encpass = password_hash($password, PASSWORD_BCRYPT);

			 if (count($errors) == 0) {
				
				//statements to insert data into the database
				$stmt = $con->prepare("INSERT INTO kuku_table (username, firstName, lastName, email, password) VALUES (?, ?, ?, ?, ?)");
				$stmt->bind_param("sssss", $username, $firstName, $lastName, $email, $encpass);

				if ($stmt->execute()) {
					// Clearing the input fields after a successful submission
					$username = "";
					$firstName = "";
					$lastName = "";
					$email = "";
					$password = "";
					$confirmPassword = "";

					echo "<script>alert('Your data has been submitted successfully');</script>";
				}else {
					echo "<script>alert('Failed')</script>";
			 	}
				 $stmt->close();
			 }
	   
    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title> ChickenRealm</title>
	<link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css"> 
    <link rel="stylesheet" href="main.css">
	<link rel="stylesheet" href="form.css">
	<link rel="stylesheet" href="eye.css">
</head>
<body>
	 <div class="container">
		<header>
			<a href="index.php"><img src="person.png" alt="Chicken logo" class = "logo1"><p class = "logo">Chicken<span>Realm</span></p></a>
			<nav>
				<ul>
					<li><a href="index.php">Home</a></li>
					<li><a href="products.php">Products</a></li>
					<li><a href="services.php">Services</a></li>
					<li><a href="contact.php" class="active">Contacts</a></li>
				</ul>
			</nav>
			</header>
			<br><br><br>
			<br><br>
			<div class="feature flip">
				
				<div class="content">
					<h2>Your Gateway to Chicken Farming Solutions</h2>
					<p class="description">
						Contact us for personalized assistance, product inquiries,
						or to discuss your poultry farm's needs. We're committed 
						to providing the support you need to thrive in chicken farming.
					</p>
				</div>

				<div class = "form_container">
				<form action="<?php echo $_SERVER['PHP_SELF'];?>" method = "POST" id = "login_form">
					<h3>Signup</h3>
					
					<label for="username">Username</label>
					<input type="text" name="username" id="username" value="<?php echo isset($username) ? $username : ''; ?>">
					<p class="error"><?php if(isset($errors['username'])) echo $errors['username'];?></p>

					<label for="firstName">First Name</label>
					<input type="text" name="firstName" id="firstName" value="<?php echo isset($firstName) ? $firstName : ''; ?>">
					<p class="error"><?php if(isset($errors['firstName'])) echo $errors['firstName'];?></p>

					<label for="lastName">Last Name</label>
					<input type="text" name="lastName" id="lastName" value="<?php echo isset($lastName) ? $lastName : ''; ?>">
					<p class="error"><?php if(isset($errors['lastName'])) echo $errors['lastName'];?></p>

					<label for="email">Email</label>
					<input type="email" name="email" id="email" value="<?php echo isset($email) ? $email : ''; ?>">
					<p class="error"><?php if(isset($errors['email'])) echo $errors['email'];?></p>

					<div class = "password-input-container">
						<label for = "password">Password</label>
						<input type = "password" name = "password" class="form-control" id="id_password" value="<?php echo isset($password) ? $password : ''; ?>">
						<i class="fas fa-eye-slash" id="togglePassword"></i>
					</div>
					<p class = "error"><?php if(isset($errors['password'])) echo $errors['password'];?></p>

					<div class = "password-input-container">
						<label for = "confirmPassword">Confirm Password</label>
						<input type="password" name="confirmPassword" id="id_confirm_password" value="<?php echo isset($confirmPassword) ? $confirmPassword : ''; ?>">
						<i class="fas fa-eye-slash password-toggle" id="toggleconfirmPassword"></i>
					</div>
					<p class = "error"><?php if(isset($errors['confirmPassword'])) echo $errors['confirmPassword'];?></p>

					<button type = "submit" name = "submit" class = "submit">Submit</button>

					<p class = "check">Already have an account? <a href = "login.php">Login</a></p>
				</form>
			</div>
			</div>

			<script src = "eye.js" defer></script>

			<h3 class = "foot_content">chickenrealm@gmail.com</h3>
			<footer class = "foot">
			
				<ul>
					<li><a href="index.php">Home</a></li>
					<li><a href="products.php">Products</a></li>
					<li><a href="services.php">Services</a></li>
					<li><a href="contact.php" class = "active">Contacts</a></li>	
				</ul>
			</footer>

			<footer class = "footer2">
				<p class = "copyright">Copyright &copy; 2023 ChickenRealm-Privacy Policy and Terms of Services </p>
			</footer>
</body>
</html>
