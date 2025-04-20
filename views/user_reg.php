<!DOCTYPE html>
<html lang="en">
<head>

	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<link rel="stylesheet" href="views/style.css">
	<script defer src="views/js/AJAX_register_validator.js"></script>

	<title>Register - mpNotes</title>

</head>
<body>

	<header>
		<?php include 'page/header.php' ?>
	</header>

	<main id="regm">

		<div></div>

		<div>

			<form class="col" action="Controllers/auth_controller.php?action=register" method="post">

				<h2>Register here!</h2>

				<!-- An incrusted PHP script to show errors, that don't violate the MVC pattern -->
				<?php session_start(); if(isset($_SESSION['error'])): ?>
					<div class="errs col">
						<h3>Errors:</h3>
						<ul>
							<?php
							$errors = explode(", ", $_SESSION['error']);
							foreach($errors as $error): ?>
								<li><?php echo htmlspecialchars($error); ?></li>
							<?php endforeach; ?>
						</ul>
					</div>
				<?php unset($_SESSION['error']); endif; ?>

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
						<img src="views/svg/at-sign.svg" alt="" srcset="">
						<label for="email_form">Email</label>
					</div>
					<input type="email" id="email_form" name="email_form" maxlength="50" placeholder="mail@email.com" required>
					<small id="email_info">Type a valid email!</small>
				</div>

				<div class="col">
					<div class="row">
						<img src="views/svg/more-horizontal.svg" alt="" srcset="">
						<label for="password_form">Password</label>
					</div>
					<input type="password" id="password_form" name="password_form" minlength="8" maxlength="30" placeholder="****" required>
				</div>

				<div class="col">
					<div class="row">
						<img src="views/svg/more-horizontal.svg" alt="" srcset="">
						<label for="password_check_form">Password (Double check) </label>
					</div>
					<input type="password" id="password_check_form" name="password_check_form" minlength="8" maxlength="30" placeholder="****" required>
					<small id="password_info" >Min 8 characters</small>
				</div>

				<button class="btn-man" type="submit">Register</button>
				<a href="login.php">Do you have an user?</a>
				<a href="recover.php">I need help!</a>

			</form>

		</div>

	</main>

</body>
</html>