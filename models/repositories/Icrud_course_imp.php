<?php

	require_once 'Icrud.php';
	require_once __DIR__ . '/../entities/course.php';
	require_once '../utils/database/Iconn_imp.php';

	class Icrud_course_imp implements Icrud {

		public function queryID($id) {

			$sql = "SELECT * FROM courses WHERE course_id = $id";

			$conn = conn_imp::getInstance();
			$conn->connect();

			$result = $conn->query($sql);

			if ( $result === false ) {
				throw new Exception("Query failed → " . $conn->getError());
			}

			if ( $result->num_rows > 0 ) {

				while ( $row = $result->fetch_assoc() ) {

					$course = new course(

						$row['course_id'],
						$row['user_id'],
						$row['name'],
						$row['full_name'],
						$row['description'],
						$row['knowledge_area'],
						$row['career'],
						$row['credits'],
						$row['thematic_content'],
						$row['semester'],
						$row['professor']

					);

					return $course;

				}

			} else {
				return null;
			}

		}

		public function selectAll() {

			$sql = "SELECT * FROM courses";

			$conn = conn_imp::getInstance();
			$conn->connect();

			$result = $conn->query($sql);

			if( $result === false ) {
				throw new Exception("Query failed → " . $conn->getError());
			}

			$courses = array();

			if ( $result->num_rows > 0 ) {

				while ( $row = $result->fetch_assoc() ) {

					$course = new course(

						$row['course_id'],
						$row['user_id'],
						$row['name'],
						$row['full_name'],
						$row['description'],
						$row['knowledge_area'],
						$row['career'],
						$row['credits'],
						$row['thematic_content'],
						$row['semester'],
						$row['professor']

					);

					array_push($courses, $course);

				}

				return $courses;

			} else {
				return null;
			}

		}

		public function insert($object) {

			try {

				$sql = "INSERT INTO courses (user_id, name, full_name, description, knowledge_area, career, credits, thematic_content, semester, professor) VALUES (

					'" . $object->getUserID() . "',
					'" . $object->getName() . "',
					'" . $object->getFullName() . "',
					'" . $object->getDescription() . "',
					'" . $object->getKnowledgeArea() . "',
					'" . $object->getCareer() . "',
					" . $object->getCredits() . ",
					'" . $object->getThematicContent() . "',
					'" . $object->getSemester() . "',
					'" . $object->getProfessor() . "'

				)";

				$conn = conn_imp::getInstance();
				$conn->connect();

				if ($conn->update($sql) === false) {
					throw new Exception("Insert failed → " . $conn->getError());
				}

				return true;

			} catch (Exception $e) {
				throw new Exception("Error inserting course: " . $e->getMessage());
			}

		}

		public function deleteID($id) {

			$sql = "DELETE FROM courses WHERE course_id = $id";

			$conn = conn_imp::getInstance();
			$conn->connect();

			$conn->update($sql);

		}

		public function update($object) {

			$sql = "UPDATE courses SET
			name = '" . $object->getUserID() . "',
			name = '" . $object->getName() . "',
			full_name = '" . $object->getFullName() . "',
			description = '" . $object->getDescription() . "',
			knowledge_area = '" . $object->getKnowledgeArea() . "',
			career = '" . $object->getCareer() . "',
			credits = " . $object->getCredits() . ",
			thematic_content = '" . $object->getThematicContent() . "',
			semester = " . $object->getSemester() . ",
			professor = '" . $object->getProfessor() . "'
			WHERE course_id = " . $object->getCourseId();

			$conn = conn_imp::getInstance();
			$conn->connect();

			$conn->update($sql);

		}

		public function total() {

			$sql = "SELECT COUNT(*) FROM courses";

			$conn = conn_imp::getInstance();
			$conn->connect();

			$result = $conn->query($sql);

			if ($result->num_rows > 0) {
				$row = $result->fetch_assoc();
				return $row['COUNT(*)'];
			} else {
				throw new Exception("No courses found");
			}

		}

	}

?>