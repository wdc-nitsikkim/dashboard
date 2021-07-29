<?php
    namespace App;

    class CustomHelper {
        private static $GLOBAL_CONSTS = [
            'session_map' => [
                'selectedDepartment' => 'department.selected',
                'selectedSubject' => 'subject.selected'
            ],
            'permission_map' => [
                'read' => 'r',
                'create' => 'c',
                'update' => 'u',
                'delete' => 'd'
            ]
        ];

        public static function checkFileInput($name) {
            return (isset($_FILES[$name]) &&
                $_FILES[$name]['name'] != '' &&
                $_FILES[$name]['size'] != 0);
        }

        public static function formatFileName($str, $appendTimestamp = true) {
            define('FILENAME_MAX_LEN', 15);
            $tmp = camel_case(mb_substr(\pathinfo($str, PATHINFO_FILENAME), 0, FILENAME_MAX_LEN));
            return ($appendTimestamp ? $tmp . '_' . time() : $tmp);
        }

        public static function getPermissionConstants() {
            return self::$GLOBAL_CONSTS['permission_map'];
        }

        public static function getSessionConstants() {
            return self::$GLOBAL_CONSTS['session_map'];
        }

        public static function test() {
            return 'Test';
        }
    }
?>
