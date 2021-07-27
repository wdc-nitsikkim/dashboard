<?php
    namespace App;

    /* add helper functions here */

    class CustomHelper {
        private static $PERMISSIONS_MAP = [
            'read'=> 'r',
            'write'=> 'w',
            'delete'=> 'd'
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

        /* get indexed array of '$extract' coloumn from sql '$rows' */
        public static function array_val_from_rows(array $rows, $extract) {
            $tmp = [];
            foreach ($rows as $val) {
                array_push($tmp, $val[$extract]);
            }
            return $tmp;
        }

        public static function get_permission_constants() {
            return self::$PERMISSIONS_MAP;
        }
    }
?>
