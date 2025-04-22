<!-- An incrusted PHP script that don't violate the MVC pattern -->
<?php

	require_once 'controllers/validation_middleware_controller.php';
	ValidationMiddleware::validateAccess();

?>