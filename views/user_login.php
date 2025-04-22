<!-- An incrusted PHP script that don't violate the MVC pattern -->
<?php

	require_once 'controllers/validation_middleware_controller.php';
	ValidationMiddleware::validateAccess();

?>

<!DOCTYPE html>
<html lang="en">
<head>

	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<link rel="stylesheet" href="views/style.css">
	<script defer src="views/js/AJAX_login_validator.js"></script>

	<title>Login - mpNotes</title>

</head>
<body>

	<header>
		<?php include 'page/header.php' ?>
	</header>

	<main id="logm">

		<div></div>

		<div>

			<form class="col" action="Controllers/auth_controller.php?action=login" method="post">

				<h2>Login here!</h2>

				<!-- An incrusted PHP script that don't violate the MVC pattern -->
				<?php if( isset( $_SESSION['success'] ) ): ?>
					<div class="note col">
						<h3>Notice:</h3>
						<p><?php echo $_SESSION['success']; ?></p>
					</div>
				<?php unset ($_SESSION['success'] ); endif; ?>

				<?php if( isset( $_SESSION['error'] ) ): ?>
					<div class="errs col">
						<h3>Errors:</h3>
						<ul>
							<?php
								$errors = is_array( $_SESSION['error'] ) ? $_SESSION['error'] : [$_SESSION['error']];
								foreach($errors as $error):
							?>
								<li><?php echo htmlspecialchars($error); ?></li>
							<?php endforeach; ?>
						</ul>
					</div>
				<?php unset( $_SESSION['error'] ); endif; ?>

				<div class="col">
					<div class="row">
						<img src="views/svg/user.svg" alt="" srcset="">
						<label for="username_form">Username</label>
					</div>
					<input type="text" id="username_form" name="username_form" minlength="4" maxlength="15" placeholder="username" required>
					<small id="username_info">Min 4 characters / You can't change it later!</small>
				</div>

				<div class="col">
					<div class="row">
						<img src="views/svg/more-horizontal.svg" alt="" srcset="">
						<label for="password_form">Password</label>
					</div>
					<input type="password" id="password_form" name="password_form" minlength="8" maxlength="30" placeholder="****" required>
					<small id="password_info" >Min 8 characters</small>
				</div>

				<button class="btn-man" type="submit">Login</button>
				<a href="register.php">Don't have an user?</a>
				<a href="recover.php">I need help!</a>

			</form>

		</div>

	</main>

</body>
</html>