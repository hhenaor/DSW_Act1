<?php

    require_once 'Icrud.php';
    require_once __DIR__ . '/../entities/user_status.php';
    require_once '../../utils/database/Iconn_imp.php';

    class Icrud_user_status_imp implements Icrud {

        public function queryID($id) {

            $sql = "SELECT * FROM user_statuses WHERE user_id = '$id'";

            $conn = conn_imp::getInstance();
            $conn->connect();

            $result = $conn->query($sql);

            $row = $result->fetch_assoc();

            if ( count($row) > 0 ) {
                $user_status = new UserStatus(
                    $row['user_id'],
                    $row['status']
                );
                return $user_status;
            } else {
                throw new Exception("User status not found");
            }

        }

        public function selectAll() {

            $sql = "SELECT * FROM user_statuses";

            $conn = conn_imp::getInstance();
            $conn->connect();

            $result = $conn->query($sql);

            $user_statuses = array();

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $user_status = new UserStatus(
                        $row['user_id'],
                        $row['status']
                    );
                    array_push($user_statuses, $user_status);
                }
                return $user_statuses;
            } else {
                throw new Exception("No user statuses found");
            }

        }

        public function insert($object) {

            $sql = "INSERT INTO user_statuses (user_id, status) VALUES (
            '" . $object->getUserId() . "',
            '" . $object->getStatus() . "'
            )";

            $conn = conn_imp::getInstance();
            $conn->connect();

            $conn->update($sql);

        }

        public function deleteID($id) {

            $sql = "DELETE FROM user_statuses WHERE user_id = '$id'";

            $conn = conn_imp::getInstance();
            $conn->connect();

            $conn->update($sql);

        }

        public function update($object) {

            $sql = "UPDATE user_statuses SET
            status = '" . $object->getStatus() . "'
            WHERE user_id = '" . $object->getUserId() . "'";

            $conn = conn_imp::getInstance();
            $conn->connect();

            $conn->update($sql);

        }

        public function total() {

            $sql = "SELECT COUNT(*) FROM user_statuses";

            $conn = conn_imp::getInstance();
            $conn->connect();

            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                $row = $result->fetch_row();
                return $row[0];
            } else {
                throw new Exception("No user statuses found");
            }

        }

    }

?>