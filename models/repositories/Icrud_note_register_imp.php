<?php

    require_once 'Icrud.php';
    require_once __DIR__ . '/../entities/note_register.php';
    require_once '../../utils/database/Iconn_imp.php';

    class Icrud_note_register_imp implements Icrud {

        public function queryID($id) {

            $sql = "SELECT * FROM note_registers WHERE note_id = $id";

            $conn = conn_imp::getInstance();
            $conn->connect();

            $result = $conn->query($sql);

            $row = $result->fetch_assoc();

            if ( count($row) > 0 ) {
                $note_register = new NoteRegister(
                    $row['note_id'],
                    $row['note_rule_id'],
                    $row['note_value'],
                    $row['comment']
                );
                return $note_register;
            } else {
                throw new Exception("Note register not found");
            }

        }

        public function selectAll() {

            $sql = "SELECT * FROM note_registers";

            $conn = conn_imp::getInstance();
            $conn->connect();

            $result = $conn->query($sql);

            $note_registers = array();

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $note_register = new NoteRegister(
                        $row['note_id'],
                        $row['note_rule_id'],
                        $row['note_value'],
                        $row['comment']
                    );
                    array_push($note_registers, $note_register);
                }
                return $note_registers;
            } else {
                throw new Exception("No note registers found");
            }

        }

        public function insert($object) {

            $sql = "INSERT INTO note_registers (note_id, note_rule_id, note_value, comment) VALUES (
            '". $object->getNoteId() . "',
            '" . $object->getNoteRuleId() . "',
            '" . $object->getNoteValue() . "',
            '" . $object->getComment() . "'
            )";

            $conn = conn_imp::getInstance();
            $conn->connect();

            $conn->update($sql);

        }

        public function deleteID($id) {

            $sql = "DELETE FROM note_registers WHERE note_id = $id";

            $conn = conn_imp::getInstance();
            $conn->connect();

            $conn->update($sql);

        }

        public function update($object) {

            $sql = "UPDATE note_registers SET
            note_rule_id = '" . $object->getNoteRuleId() . "',
            note_value = '" . $object->getNoteValue() . "',
            comment = '" . $object->getComment() . "'
            WHERE note_id = '" . $object->getNoteId() . "'";

            $conn = conn_imp::getInstance();
            $conn->connect();

            $conn->update($sql);

        }

        public function total() {

            $sql = "SELECT COUNT(*) FROM note_registers";

            $conn = conn_imp::getInstance();
            $conn->connect();

            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                $row = $result->fetch_row();
                return $row[0];
            } else {
                throw new Exception("No note registers found");
            }

        }

    }

?>