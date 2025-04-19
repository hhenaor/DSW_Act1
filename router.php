<?php

	/*
		Stupid Ass PHP Router Implementation
		Probably not the right way to do so, but
		it works on my machine lol
	*/

	if (isset($path)) {

		// * Route for "plain site url" as landing.php view
		// - site.com/
		// - site.com/index.php
		if( $path == "index" ) { if ($_SERVER['PHP_SELF'] == '/index.php') { require "views/landing.php"; } }

		// * Route for "login url" as user_login.php view
		// - site.com/login.php
		if( $path == "login" ) { if ($_SERVER['PHP_SELF'] == '/login.php') { require "views/user_login.php"; } }

		// * Route for "register url" as user_reg.php view
		// - site.com/register.php
		if( $path == "reg" ) { if ($_SERVER['PHP_SELF'] == '/register.php') { require "views/user_reg.php"; } }

		// * Route for "register url" as user_reg.php view
		// - site.com/register.php
		if( $path == "check" ) { if ($_SERVER['PHP_SELF'] == '/checkpoint.php') { require "views/user_validation.php"; } }

	} else {

		if ($_SERVER['PHP_SELF'] == '/router.php') {
			header('Location: ..');
		}

	}

?>