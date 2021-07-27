<?php
    namespace App;

    /* add helper functions here */

    class CustomHelper {
        public static function check_file_input($name) {
            return (isset($_FILES[$name]) &&
                $_FILES[$name]["name"] != "" &&
                $_FILES[$name]["size"] != 0);
        }

        public static function format_file_name($str, $append_timestmp = true) {
            define('FILENAME_MAX_LEN', 15);
            $tmp = camel_case(mb_substr(\pathinfo($str, PATHINFO_FILENAME), 0, FILENAME_MAX_LEN));
            return ($append_timestmp ? $tmp . '_' . time() : $tmp);
        }
    }
?>
