<?php
    namespace App;

    use Carbon\Carbon;

    class CustomHelper {
        /**
         * Contains constant values to be provided globally
         *
         * @static array
         */
        private static $GLOBAL_CONSTS = [
            'sessionMap' => [
                'selectedDepartment' => 'department.selected',
                'selectedSubject' => 'subject.selected',
                'selectedBatch' => 'batch.selected'
            ],
            'permissionMap' => [
                'read' => 'r',
                'create' => 'c',
                'update' => 'u',
                'delete' => 'd'
            ]
        ];

        /**
         * Checks file input
         *
         * @param string $name
         * @return bool
         */
        public static function checkFileInput($name) {
            return (isset($_FILES[$name]) &&
                $_FILES[$name]['name'] != '' &&
                $_FILES[$name]['size'] != 0);
        }

        /**
         * Reformats $str according to given format
         *
         * @param string $str
         * @param bool $appendTimestamp
         * @return string
         */
        public static function formatFileName($str, $appendTimestamp = true) {
            define('FILENAME_MAX_LEN', 15);
            $tmp = camel_case(mb_substr(\pathinfo($str, PATHINFO_FILENAME), 0, FILENAME_MAX_LEN));
            return ($appendTimestamp ? $tmp . '_' . time() : $tmp);
        }

        /**
         * Returns permission contants used in database
         *
         * @return array
         */
        public static function getPermissionConstants() {
            return self::$GLOBAL_CONSTS['permissionMap'];
        }

        /**
         * Returns common session keys used in app
         *
         * @return array
         */
        public static function getSessionConstants() {
            return self::$GLOBAL_CONSTS['sessionMap'];
        }

        /**
         * Converts given UTC date string to app timezone date string
         *
         * @param string $date
         * @return string
         */
        public static function utcToAppTimezone($date) {
            return $date ? Carbon::createFromTimestamp(strtotime($date))
                ->timezone(env('APP_TIMEZONE'))
                ->toDateTimeString() : null;
        }

        public static function test() {
            return 'Test';
        }
    }
?>
