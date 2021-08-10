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
            $tmp = kebab_case(mb_substr(\pathinfo($str, PATHINFO_FILENAME), 0, FILENAME_MAX_LEN));
            return ($appendTimestamp ? $tmp . '-' . time() : $tmp);
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
         * @param string $format (optional)
         * @return string
         */
        public static function utcToAppTimezone($date, $format = null) {
            return $date ? Carbon::createFromTimestamp(strtotime($date))
                ->timezone(config('app.timezone_local'))
                ->format($format ?? config('app.date_format')) : null;
        }

        /**
         * Get current datetime string according to app timezone
         *
         * @param string $format (optional)
         * @return string
         */
        public static function getCurrentDate($format = null) {
            return Carbon::now(config('app.timezone_local'))
                ->format($format ?? config('app.date_format'));
        }

        /**
         * Convert date to utc datetime string
         *
         * @param string $date
         * @param string $format
         * @return string
         */
        public static function dateToUtc($date, $format = 'Y-m-d H:i:s') {
            return $date ? Carbon::createFromTimestamp(strtotime($date))
                ->timezone('UTC')
                ->format($format ?? config('app.date_format')) : null;
        }

        /**
         * Prepare a search query using submitted data & map
         *
         * @param Illuminate\Database\Eloquent\Builder $base
         * @param array $data  Submitted data by user
         * @param array $map  Comparison type map
         * @return Illuminate\Database\Eloquent\Builder
         */
        public static function getSearchQuery($base, $data, $map) {
            foreach ($map as $key => $val) {
                if (is_null($data[$key] ?? null)) {
                    continue;
                }
                $curr = $data[$key];

                switch ($val) {
                    case 'strict': $base->where($key, $curr); break;
                    case 'like': $base->where($key, 'like', '%' . $curr . '%'); break;
                    case 'date':
                        $compareKey = $key . '_compare';
                        $compare = (isset($data[$compareKey]) && !is_null($data[$compareKey]))
                            ? $data[$compareKey] : 'strict';
                        if ($compare == 'before')
                            $base->whereDate($key, '<=', $curr);
                        else if ($compare == 'after')
                            $base->whereDate($key, '>=', $curr);
                        else
                            $base->whereDate($key, $curr);
                        break;
                    default: break;
                }
            }
            return $base;
        }

        public static function test() {
            return 'Test';
        }
    }
?>
