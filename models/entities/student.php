<?php

	// DTO
	class student {

		private $username;
		private $password;
		private $name;
		private $email;

		// Constructor
		public function __construct($username, $password, $name, $email) {
			$this->username = $username;
			$this->password = $password;
			$this->name = $name;
			$this->email = $email;
		}

		// getters
		public function getUsername() { return $this->username; }
		public function getPassword() { return $this->password; }
		public function getName() { return $this->name; }
		public function getEmail() { return $this->email; }

		// setters
		// public function setUsername($username) { $this->username = $username; }
		// ! Disabled username setter to prevent overwriting the primary key
		public function setPassword($password) { $this->password = $password; }
		public function setName($name) { $this->name = $name; }
		public function setEmail($email) { $this->email = $email; }

	}

?>