<?php

	// * service for dashboard operations + AJAX responses

	require_once '../models/repositories/Icrud_course_imp.php';
	require_once '../models/repositories/Icrud_note_rule_imp.php';
	require_once '../models/repositories/Icrud_user_registration_imp.php';

	class DashboardService {

		private $courseRepo;
		private $noteRuleRepo;
		private $userRegistrationRepo;

		public function __construct() {

			$this->courseRepo = new Icrud_course_imp();
			$this->noteRuleRepo = new Icrud_note_rule_imp();
			$this->userRegistrationRepo = new Icrud_user_registration_imp();

		}

		// > DataCourse <

		// - validate if inputs are valid data types
		// * needs JSON
		// ! retun string or bool
		public function validateDataCourse($data) {

			try {

				// sanitize inputs
				foreach ($data as $key => $value) {
					$data[$key] = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
				}

				// validate all fields are not empty and are correct type
				foreach ($data as $key => $value) {

					// check if empty
					if (empty($value)) {
						return "Field '$key' cannot be empty";
					}

					if ($key === 'credits') {

						if (!is_numeric($value)) {
							return "Credits needs to be a number";
						}

					} else {

						if (!is_string($value)) {
							return "All fields except credits must be strings";
						}

					}

				}
				return true;

			} catch (Exception $e) {
				return "Error validating course data (" . $e->getMessage();
			}

		}

		// - check if course exist on database
		// * needs JSON
		// ! return string or bool
		public function existCourse($data) {

			try {

				// sanitize inputs
				foreach ($data as $key => $value) {
					$data[$key] = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
				}

				// get courses via fullname
				$result = $this->courseRepo->queryID($data['course_id']);

				if ( $result == null ) {
					return "Course not found.";
				} else {
					return true;
				}

			} catch (Exception $e) {
				return "Error getting course (" . $e->getMessage();
			}

		}

		// - get course id
		// * needs string
		// ! return string or bool
		public function getCourseID($data) {

			try {

				// sanitize inputs
				// $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');

				// get course id
				$result = $this->courseRepo->queryID($data);

				if ( $result === null ) {
					return "Course not found.";
				} else {
					return $result->getCourseId();
				}

			} catch (Exception $e) {
				return "Error getting course ID (" . $e->getMessage();
			}

		}

		// - create course
		// * needs JSON
		// ! return string or bool
		public function createCourse($data, $user_id) {

			try {

				// sanitize inputs
				foreach ($data as $key => $value) {
					$data[$key] = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
				}

				// create course obj
				$newCourse = new Course(

					null,
					$user_id,
					$data['name'],
					$data['full_name'],
					$data['description'],
					$data['knowledge_area'],
					$data['career'],
					$data['credits'],
					$data['thematic_content'],
					$data['semester'],
					$data['professor']

				);

				// insert course into database
				$this->courseRepo->insert($newCourse);

				return true;

			} catch (Exception $e) {
				return "Error creating course (" . $e->getMessage();
			}

		}

		// - get all courses data
		// * needs nothing
		// ! return array or bool
		public function getAllCoursesData() {

			try {

				// get all courses
				$courses = $this->courseRepo->selectAll();

				if ($courses === null) {
					return [];
				}

				// create empty array
				$coursesData = [];

				foreach ($courses as $course) {

					// construct array of courses
					$coursesData[] = [

						'course_id' => $course->getCourseId(),
						'user_id' => $course->getUserId(),
						'name' => $course->getName(),
						'full_name' => $course->getFullName(),
						'description' => $course->getDescription(),
						'knowledge_area' => $course->getKnowledgeArea(),
						'career' => $course->getCareer(),
						'credits' => $course->getCredits(),
						'thematic_content' => $course->getThematicContent(),
						'semester' => $course->getSemester(),
						'professor' => $course->getProfessor()

					];

				}

				return $coursesData;

			} catch (Exception $e) {
				return "Error fetching courses: " . $e->getMessage();
			}

		}

		// > NoteRule <

		// - validate if note rule inputs are valid
		// * needs JSON
		// ! return string or bool
		public function validateDataNoteRule($data) {

			try {

				// sanitize inputs
				foreach ($data as $key => $value) {
					$data[$key] = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
				}

				// validate note_count
				if (!isset($data['note_count']) || !is_numeric($data['note_count'])) {
					return "Note count must be a number.";
				}

				$noteCount = intval($data['note_count']);
				if ($noteCount < 1 || $noteCount > 100) {
					return "Note count must be between 1 and 100.";
				}

				// validate max_value
				if (!isset($data['max_value']) || !is_numeric($data['max_value'])) {
					return "Max value must be a number.";
				}

				$maxValue = floatval($data['max_value']);
				if ($maxValue < 0.99 || $maxValue > 999.99) {
					return "Max value must be between 0.99 and 999.99.";
				}

				return true;

			} catch (Exception $e) {
				return "Error validating note rule data (" . $e->getMessage();
			}

		}
		// - create note rule
		// * needs JSON and string
		// ! return string
		public function createNoteRule($data) {

			try {

				// sanitize inputs
				// foreach ($data as $key => $value) {
				// 	$data[$key] = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
				// }

				// create obj
				$newNoteRule = new NoteRule (

					intval($data['course_id']),
					intval($data['note_count']),
					floatval($data['max_value'])

				);

				// create note rule
				$this->noteRuleRepo->insert($newNoteRule);

				return true;

			} catch (Exception $e) {
				return "Error creating note rule (" . $e->getMessage();
			}

		}

		// - get note rule
		// * needs string
		// ! return string
		public function getNoteRuleID($data) {

			try {

				$result = $this->noteRuleRepo->queryID($data);

				if ( $result == null ) {
					return "Note rule not found.";
				} else {
					return $result->getNoteRuleId();
				}

			} catch (Exception $e) {
				return "Error getting note rule ID (" . $e->getMessage();
			}

		}

		// - get last note rule
		// * needs nothing
		// ! return string
		public function getLastNoteRuleID() {

			try {

				$result = $this->noteRuleRepo->selectAll();

				if ( $result == null ) {
					return "Note rule not found.";
				} else {

					$maxId = 0;
					foreach ($result as $noteRule) {

						$currentId = $noteRule->getNoteRuleId();

						if ($currentId > $maxId) {
							$maxId = $currentId;
						}

					}

					return $maxId;

				}

			} catch (Exception $e) {
				return "Error getting last note rule ID (" . $e->getMessage() . ")";
			}

		}

		// > UserRegistrarion <

		// - link course to user
		// * needs 2 strings
		// ! return string or bool
		public function createUserRegistration($user_id, $course_id) {

			try {

				// create obj
				$newUserRegistration = new UserRegistration(

					$user_id,
					intval($course_id)

				);

				// create user registration
				$this->userRegistrationRepo->insert($newUserRegistration);

				return true;

			} catch (Exception $e) {
				return "Error creating user registration (" . $e->getMessage();
			}

		}

	}

?>