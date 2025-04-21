<!DOCTYPE html>
<html lang="en">
<head>

	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<link rel="stylesheet" href="views/style.css">

	<title>Checkpoint - mpNotes</title>

</head>
<body>

	<header>
		<?php include 'page/header.php' ?>
	</header>

	<main id="regm">

		<div></div>

		<div>

			<form class="col" action="Controllers/account_controller.php?action=verify" method="post">

				<h2>Verification process</h2>

				<!-- An incrusted PHP script to show errors, that don't violate the MVC pattern -->
				<?php session_start(); if(isset($_SESSION['user_id'])): ?>
					<div class="note col">
						<h3>Notice:</h3>
						<?php echo "Hi, " . htmlspecialchars($_SESSION['user_id']) .".<br>A verification code was send to your linked email (".$_SESSION['special']."). To start using mpNotes services enter the code in the verification field."; ?>
					</div>
				<?php endif; ?>

				<?php if(isset($_SESSION['error'])): ?>
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
						<img src="views/svg/user-check.svg" alt="" srcset="">
						<label for="usercode_form">Verification code</label>
					</div>
					<input type="text" id="usercode_form" name="usercode_form" minlength="6" maxlength="6" placeholder="Abc123" required>
					<small id="username_info">Type your user code.</small>
				</div>

				<br>

				<h4>User nickname</h4>

				<p>You have already defined your unique username, now enter a nickname for your account. Useful if you can't register the unique user you wanted. This is REQUIRED.</p>

				<div class="col">
					<div class="row">
						<img src="views/svg/edit-3.svg" alt="" srcset="">
						<label for="nickname_form">User nickname</label>
					</div>
					<input type="nickname" id="nickname_form" name="nickname_form" minlength="4" maxlength="30" placeholder="epic joe 24" required>
					<small id="nickname_info">Min 4 characters</small>
				</div>

				<button class="btn-man" type="submit">Verify</button>
				<a href="recover.php">I need help!</a>

			</form>

		</div>

	</main>

</body>
</html>