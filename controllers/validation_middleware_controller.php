<?php

	session_start();

	class ValidationMiddleware {

		// user can access without login
		private static $publicPages = [
			'login.php',
			'register.php',
			'recover.php'
		];

		// user must be logged
		private static $privatePages = [
			'dashboard.php',
			'checkpoint.php'
		];

		public static function validateAccess() {

			$currentPage = basename($_SERVER['PHP_SELF']);

			// if has session and tries to access public pages
			if(isset($_SESSION['user_id']) && in_array($currentPage, self::$publicPages)) {
				header("Location: dashboard.php");
				exit;
			}

			// if has no session and tries to access private pages
			if(!isset($_SESSION['user_id']) && in_array($currentPage, self::$privatePages)) {

				$_SESSION['error'] = "Please login first.";
				header("Location: login.php");
				exit;

			}


		}

	}

?>