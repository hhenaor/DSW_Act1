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
	<script defer src="views/js/AJAX_dashboard.js"></script>

	<title>Dashboard - mpNotes</title>

	<style>
		#overlay img {
			animation: spin 2s linear infinite;
		}

		@keyframes spin {
			0% { transform: rotate(0deg); }
			100% { transform: rotate(360deg); }
		}
	</style>

</head>
<body>
	<header>
		<?php include 'page/header.php' ?>
	</header>
	<main class="col">

		<div id="overlay"></div>

		<h1>Welcome to mpNotes</h1>

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

		<div class="dash-bor">

			<h2>My Notes</h2>

		</div>

		<div class="dash-bor">

			<div class="row cont">
				<h2>My courses</h2>
				<div class="row">
					<button class="btn-man" id="add-course-btn"> <img src="views/svg/book.svg" alt="" srcset=""> Add course</button>
				</div>
			</div>

			<p id="total-courses">Linked to 0 courses.</p>

			<div id="course-tab">

			</div>

		</div>

		<div class="dash-bor">

			<div class="row cont">
				<h2>Note rules</h2>
				<div class="row">
					<button class="btn-gry" id="add-note_rule-btn"> <img src="views/svg/plus.svg" alt="" srcset=""> Add note rule</button>
				</div>
			</div>

			<p id="total-courses">0 note rules.</p>

			<div id="course-tab">

			</div>

		</div>

		<div class="dash-bor errs">

			<h2>User settings</h2>

			<p>Danger zone!</p>

		</div>

	</main>

</body>
</html>