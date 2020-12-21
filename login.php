<?php 
	
	ob_start();

	session_start();

	$pageTitle = 'Login';

	if ( isset($_SESSION['user']) ) {

		header("Location: index.php");

	}

	include "init.php";

	//Check If User Is Coming From HTTP REQUEST [Coming From Form]

	if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {

		if ( isset($_POST['login']) ) {

			$user = $_POST['username'];

			$pass = $_POST['password'];

			$hashedPass = sha1($pass);

			$stmt = $conn -> prepare("SELECT 
											UserID, Username, Password 
									  FROM 
									  		users 
									  WHERE 
									  		Username = ? 
									  AND 
									  		Password = ? 
									 ");

			$stmt -> execute( array($user, $hashedPass) );

			$get = $stmt -> FETCH();

			$count = $stmt -> rowCount();

			if ($count > 0) {

				$_SESSION['user'] = $user;			// Username Is Unique, You Can Depend On It

				$_SESSION['uid'] = $get['UserID'];	// Register UserID In Session

				header("Location: index.php");

				exit();
			}

		} else {	// Signup Form

			$formErrors = array();

			$username 		= $_POST['username'];

			$password1 		= $_POST['password'];

			$password2	 	= $_POST['confirm-password'];

			$email 			= $_POST['email'];

			if ( isset($username) ) {

				$filteredUser = filter_var($username, FILTER_SANITIZE_STRING);

				if ( strlen($filteredUser) < 4 ) {

					$formErrors[] = "Sorry Username Must Be More Than <strong> 4 </strong> Characters";
				}

				if ( strlen($filteredUser) > 20 ) {

					$formErrors[] = "Sorry Username Must Be Less Than <strong> 20 </strong> Characters";
				}

			}

			if ( isset($password1) && isset($password2) ) {

					if ( empty($password1) ) {

						$formErrors[] = "Password Can't Be Empty";
					}

					$pass1 = sha1($password1);

					$pass2 = sha1($password2);

					if ( $pass1 !== $pass2 ) {

						$formErrors[] = "Sorry! Password Does Not Match";
					}
			}

			if ( isset($email) ) {

				$filteredEmail = filter_var($email, FILTER_SANITIZE_EMAIL);

				if ( filter_var($filteredEmail, FILTER_VALIDATE_EMAIL) != TRUE ) {

					$formErrors[] = "This Email Is Not Valid";
				}

			}

			// Check If There Are No Errors, Then Proceed The User Addition

		if ( empty($formErrors) ) {

			// Check If User Is Exists In Database

			$count = chkItems ('Username', 'users', $username);

			if ( $count > 0 ) {

				$formErrors[] = "Sorry This Username Is Exists";

			} else {

				$stmt = $conn -> prepare("INSERT INTO
										  users(Username, Password, Email, RegStatus, Date)
								  		  VALUES(:zuser, :zpass, :zemail, 0, now())
								 ");

				$stmt -> execute( 
					array(
						'zuser' 	=> $username,
						'zpass' 	=> sha1($password1),
						'zemail' 	=> $email
					) 
				);

				// Echo Success Message

				$successMsg = "Congrats! Successfully Registered";
			}
		}

	}

} 
?>
	<div class="container login-page">
		<h1 class="text-center"> 
			<span class="selected" data-class="login">Login</span> | <span data-class="signup">Sign Up</span>
		</h1>
		<!-- Start Login Form -->
		<form class="login" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
			<div class="input-container">
				<input type="text" name="username" class="form-control" placeholder="Username" autocomplete="off" required />
			</div>
			<div class="input-container">
				<input type="password" name="password" class="form-control" placeholder="Password" autocomplete="new-password" required />
			</div>
			<input type="submit" name="login" value="Login" class="btn btn-primary btn-block" />
		</form>
		<!-- End Login Form -->

		<!-- Start Signup Form -->
		<form class="signup" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
			<div class="input-container">
				<input pattern=".{4,20}" title="Username Must Be Between 4 & 20 Chars" type="text" name="username" class="form-control" placeholder="Username" autocomplete="off" />
			</div>
			<div class="input-container">
				<input type="email" name="email" class="form-control" placeholder="Email" />
			</div>
			<div class="input-container">
				<input minlength="8" type="password" name="password" class="form-control" placeholder="Password" autocomplete="new-password" />
			</div>
			<div class="input-container">
				<input minlength="8" type="password" name="confirm-password" class="form-control" placeholder="Confirm Password" autocomplete="new-password" />
			</div>
			<input type="submit" name="signup" value="Signup" class="btn btn-success btn-block" />
		</form>
		<!-- End Signup Form -->

		<div class="errors text-center">
			<?php 

				if ( ! empty($formErrors) ) {

					foreach ($formErrors as $error) {

						echo "<div class='error'>" . $error . "</div>";
				}
			}

				if ( isset($successMsg) ) {

					echo "<div class='success'>" . $successMsg . "</div>";
				}

			?>
		</div>
	</div>	<!-- Closed Container -->


<?php 
	ob_end_flush();
?>

