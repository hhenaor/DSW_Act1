<?php

    require_once 'Icrud.php';
    require_once __DIR__ . '/../entities/user_registration.php';
	require_once '../utils/database/Iconn_imp.php';

    class Icrud_user_registration_imp implements Icrud {

		/**
		 * Query a user registration by user_id and course_id
		 * @param array $id An array where $id[0] is user_id and $id[1] is course_id
		 * @return UserRegistration
		 * @throws Exception if registration not found or if array doesn't have exactly 2 elements
		 */
		public function queryID($id) {

			if (!is_array($id) || count($id) !== 2) {
				throw new Exception("ID must be an array with exactly 2 elements on the following order: user_id, course_id");
			}

			$sql = "SELECT * FROM user_registrations WHERE
					user_id = '" . $id[0] . "'
					AND course_id = " . $id[1];

			$conn = conn_imp::getInstance();
			$conn->connect();

			$result = $conn->query($sql);

			$row = $result->fetch_assoc();

			if ( count($row) > 0 ) {
				$registration = new UserRegistration(
					$row['user_id'],
					$row['course_id']
				);
				return $registration;
			} else {
				throw new Exception("User registration not found");
			}

		}

        public function selectAll() {

            $sql = "SELECT * FROM user_registrations";

            $conn = conn_imp::getInstance();
            $conn->connect();

            $result = $conn->query($sql);

            $registrations = array();

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $registration = new UserRegistration(
                        $row['user_id'],
                        $row['course_id']
                    );
                    array_push($registrations, $registration);
                }
                return $registrations;
            } else {
                throw new Exception("No user registrations found");
            }

        }

        public function insert($object) {

            $sql = "INSERT INTO user_registrations (user_id, course_id) VALUES (
            '" . $object->getUserId() . "',
            " . $object->getCourseId() . "
            )";

            $conn = conn_imp::getInstance();
            $conn->connect();

            $conn->update($sql);

        }

		/**
		 * Delete a user registration by user_id and course_id
		 * @param array $id An array where $id[0] is user_id and $id[1] is course_id
		 * @throws Exception if array doesn't have exactly 2 elements
		 */
		public function deleteID($id) {
			if (!is_array($id) || count($id) !== 2) {
			throw new Exception("ID must be an array with exactly 2 elements on the following order: user_id, course_id");
			}

			$sql = "DELETE FROM user_registrations WHERE user_id = '" . $id[0] .
			   "' AND course_id = " . $id[1];

			$conn = conn_imp::getInstance();
			$conn->connect();

			$conn->update($sql);
		}

        public function update($object) {
            throw new Exception("Update operation not supported for UserRegistration - both values are primary keys");
        }

        public function total() {

            $sql = "SELECT COUNT(*) FROM user_registrations";

            $conn = conn_imp::getInstance();
            $conn->connect();

            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                $row = $result->fetch_row();
                return $row[0];
            } else {
                throw new Exception("No user registrations found");
            }

        }

    }

?>