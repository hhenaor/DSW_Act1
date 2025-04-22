<?php

	if ( isset($path) ) {

		// - site.com/
		// - site.com/index.php
		if( $path == "index" ) { if ($_SERVER['PHP_SELF'] == '/index.php') { require "views/landing.php"; } }

		// - site.com/login.php
		if( $path == "login" ) { if ($_SERVER['PHP_SELF'] == '/login.php') { require "views/user_login.php"; } }

		// - site.com/register.php
		if( $path == "reg" ) { if ($_SERVER['PHP_SELF'] == '/register.php') { require "views/user_reg.php"; } }

		// - site.com/checkpoint.php
		if( $path == "check" ) { if ($_SERVER['PHP_SELF'] == '/checkpoint.php') { require "views/user_validation.php"; } }

		// - site.com/dashboard.php
		if( $path == "dash" ) { if ($_SERVER['PHP_SELF'] == '/dashboard.php') { require "views/dashboard.php"; } }

	} else {

		if ($_SERVER['PHP_SELF'] == '/router.php') {
			header('Location: ..');
		}

	}

?>