<?php

	// * service for header actions

	class HeaderNavigationService {

		// - for header actions
		// * needs bool
		// ! returns array
		public function getNavigationState($status) {

			if ( $status === 'login' ) {

				return [

					'<a href="login.php" class="btn-sec">Login</a>',
					'<a href="register.php" class="btn-sec">Register</a>'

				];

			} else if ( $status === 'dashboard' ) {

				return [
					'<a href="Controllers/account_controller?action=verification" class="btn-sec" data-nav="auth">Dashboard</a>',
					'<a href="Controllers/auth_controller.php?action=logout" class="btn-sec" data-nav="auth">Logout</a>',
				];

			} else {

				return [
					'<a href="Controllers/auth_controller.php?action=logout" class="btn-sec" data-nav="auth">Logout</a>',
				];

			}

		}

	}

?>