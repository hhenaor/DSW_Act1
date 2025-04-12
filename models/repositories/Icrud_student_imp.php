<?php

	require_once 'Icrud.php';
	require_once __DIR__ . '/../entities/student.php';
	require_once '../../utils/database/Iconn_imp.php';

	class Icrud_student_imp implements Icrud {

		public function queryID($id) {

			$sql = "SELECT * FROM students WHERE username = $id";

			$conn = conn_imp::getInstance();
			$conn->connect();

			$result = $conn->query($sql);

			$row = $result->fetch_assoc();

			if ( count($row) > 0 ) {
				$student = new student(
					$row['username'],
					$row['password'],
					$row['name'],
					$row['email']
				);
				return $student;
			} else {
				throw new Exception("Student not found");
			}

		}

		public function selectAll() {

			$sql = "SELECT * FROM students";

			$conn = conn_imp::getInstance();
			$conn->connect();

			$result = $conn->query($sql);

			$students = array();

			if ($result->num_rows > 0) {
				while ($row = $result->fetch_assoc()) {
					$student = new student(
						$row['username'],
						$row['password'],
						$row['name'],
						$row['email']
					);
					array_push($students, $student);
				}
				return $students;
			} else {
				throw new Exception("No students found");
			}

		}

		public function insert($object) {

			$sql = "INSERT INTO students (username, password, name, email) VALUES (
			'". $object->getUsername() . "',
			'" . $object->getPassword() . "',
			'" . $object->getName() . "',
			'" . $object->getEmail() . "'
			)";

			$conn = conn_imp::getInstance();
			$conn->connect();

			$conn->update($sql);

		}

		public function deleteID($id) {

			$sql = "DELETE FROM students WHERE username = $id";

			$conn = conn_imp::getInstance();
			$conn->connect();

			$conn->update($sql);

		}

		public function update($object) {

			$sql = "UPDATE students SET
			password = '" . $object->getPassword() . "',
			name = '" . $object->getName() . "',
			email = '" . $object->getEmail() . "'
			WHERE username = '" . $object->getUsername() . "'";

			$conn = conn_imp::getInstance();
			$conn->connect();

			$conn->update($sql);

		}

		public function total() {

			$sql = "SELECT COUNT(*) FROM students";

			$conn = conn_imp::getInstance();
			$conn->connect();

			$result = $conn->query($sql);

			if ($result->num_rows > 0) {
				$row = $result->fetch_row();
				return $row[0];
			} else {
				throw new Exception("No students found");
			}

		}

		// ? add loadUser if necessary later

	}

?>