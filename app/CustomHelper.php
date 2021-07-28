<?php
    namespace App;

    /* add helper functions here */

    class CustomHelper {
        private static $GLOBAL_CONSTS = [
            'session_map' => [
                'selectedDepartment' => 'department.selected',
                'selectedSubject' => 'subject.selected'
            ],
            'permission_map' => [
                'read' => 'r',
                'write' => 'w',
                'delete' => 'd'
            ]
        ];

        public static function check_file_input($name) {
            return (isset($_FILES[$name]) &&
                $_FILES[$name]["name"] != "" &&
                $_FILES[$name]["size"] != 0);
        }

        public static function format_file_name($str, $append_timestamp = true) {
            define('FILENAME_MAX_LEN', 15);
            $tmp = camel_case(mb_substr(\pathinfo($str, PATHINFO_FILENAME), 0, FILENAME_MAX_LEN));
            return ($append_timestamp ? $tmp . '_' . time() : $tmp);
        }

        public static function get_permission_constants() {
            return self::$GLOBAL_CONSTS['permission_map'];
        }

        public static function get_session_constants() {
            return self::$GLOBAL_CONSTS['session_map'];
        }

        public static function test() {
            return 'Test';
        }
    }
?>
