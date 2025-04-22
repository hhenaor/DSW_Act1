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

	<title>Dashboard - mpNotes</title>

</head>
<body>
	<header>
		<?php include 'page/header.php' ?>
	</header>
	<main class="col">

		<h1>Welcome to mpNotes</h1>

		<div class="dash-bor">

			<h2>Notes manager</h2>

		</div>

		<div class="dash-bor">
			<h3>User settings</h3>
		</div>

	</main>

</body>
</html>