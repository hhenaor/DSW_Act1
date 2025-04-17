<!DOCTYPE html>
<html lang="en">
<head>

	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<link rel="stylesheet" href="views/style.css">

	<title>Register - mpNotes</title>

</head>
<body>

	<header>
		<?php include 'page/header.php' ?>
	</header>

	<main id="regm">
		<!-- <div>a</div> -->
		<div>
			<form class="col" action="" method="get">
				<h2>Register here!</h2>
				<div class="col">
					<div class="row">
						<img src="views/svg/user.svg" alt="" srcset="">
						<label for="username_form">Username</label>
					</div>
					<input type="text" id="username_form" minlength="4" maxlength="15" placeholder="username" required>
					<small>Min 8 characters / You can't change it later!</small>
				</div>
				<div class="col">
					<div class="row">
						<img src="views/svg/at-sign.svg" alt="" srcset="">
						<label for="email_form">Email</label>
					</div>
					<input type="email" id="email_form" maxlength="50" placeholder="mail@email.com" required>
				</div>
				<div class="col">
					<div class="row">
						<img src="views/svg/more-horizontal.svg" alt="" srcset="">
						<label for="password_form">Password</label>
					</div>
					<input type="password" id="password_form" minlength="8" maxlength="30" placeholder="****" required>
					<small>Min 8 characters</small>
				</div>
				<div class="col">
					<div class="row">
						<img src="views/svg/more-horizontal.svg" alt="" srcset="">
						<label for="password_check_form">Password (Double check) </label>
					</div>
					<input type="password" id="password_check_form" minlength="8" maxlength="30" placeholder="****" required>
					<small>Min 8 characters</small>
				</div>
				<button class="btn-man" type="submit">Register</button>
				<a href="login.php">Already register?</a>
			</form>
		</div>
	</main>

</body>
</html>