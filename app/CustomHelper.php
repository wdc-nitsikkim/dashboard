<?php
    namespace App;

    use Carbon\Carbon;

    use Illuminate\Support\Facades\App;
    use Illuminate\Support\Facades\Auth;

    /**
     * Custom globally accessible helper functions for this app
     */
    class CustomHelper {
        /**
         * Contains constant values to be provided globally
         *
         * @static array
         */
        private static $GLOBAL_CONSTS = [
            'sessionMap' => [
                'selectedBatch' => 'batch.selected',
                'selectedSubject' => 'subject.selected',
                'selectedDepartment' => 'department.selected',
                'confirmPassword' => 'pwd_last_confirmed'
            ],
            'permissionMap' => [
                'read' => 'r',
                'create' => 'c',
                'update' => 'u',
                'delete' => 'd'
            ],
            'roles' => ['admin', 'office', 'ecell', 'tnp', 'hod',
                'faculty', 'staff', 'student'
            ],
            'siteSettings' => [
                'resultMod' => 'result_modification',
                'studentReg' => 'student_registration'
            ]
        ];

        /**
         * Reset password token length
         */
        const RESET_PWD_TOKEN_LEN = 64;

        /**
         * Constant form select-menu values used accross the app & database
         */
        const FORM_SELECTMENU = [
            'gender' => ['male', 'female', 'other'],
            'blood_group' => ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'],
            'category' => ['general', 'obc', 'obc (ncl)', 'sc', 'st', 'other'],
            'religion' => ['hinduism', 'islam', 'christianity', 'sikhism',
                'buddhism', 'jainism', 'other'],
            'marking_schemes' => ['cgpa', 'percentage'],
            'school_boards' => ['cbse', 'icse', 'other'],
        ];

        /**
         * Checks file input
         *
         * @param string $name
         * @return bool
         */
        public static function checkFileInput($name) {
            return (isset($_FILES[$name])
                && $_FILES[$name]['name'] != ''
                && $_FILES[$name]['size'] != 0);
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

        public static function getInversePermissionMap() {
            return array_flip(self::$GLOBAL_CONSTS['permissionMap']);
        }

        /**
         * Returns array of roles used in database
         *
         * @return array
         */
        public static function getRoles() {
            return self::$GLOBAL_CONSTS['roles'];
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
         * Returns site settings keys used in app -> db
         *
         * @return array
         */
        public static function getSiteSettingKeys() {
            return self::$GLOBAL_CONSTS['siteSettings'];
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
         * Get value of site setting (by name) stored in db
         *
         * @param string $name
         * @return string|null
         */
        public static function getSiteSetting($name) {
            $siteSettingKeys = self::$GLOBAL_CONSTS['siteSettings'];
            if (! array_key_exists($name, $siteSettingKeys)) {
                return false;
            }

            $siteSettings = App::make('site_settings');
            $setting = $siteSettings->where('name', $siteSettingKeys[$name])->first();

            return $setting->value ?? null;
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

        /**
         * Check if given session variables exist else redirect appropriately to pre-defined routes
         *
         * @param string $redirectHandler  Original redirect handler
         * @param array $checkKeys  Session variables to check
         * @return boolean|\Illuminate\Http\RedirectResponse
         */
        public static function sessionCheckAndRedirect($redirectHandler, array $checkKeys) {
            $sessionKeys = self::$GLOBAL_CONSTS['sessionMap'];
            $redirectParameter = [
                'redirect' => $redirectHandler
            ];
            $selectRoutes = [
                'selectedBatch' => route('admin.batch.select', $redirectParameter),
                'selectedSubject' => route('admin.subjects.select', $redirectParameter),
                'selectedDepartment' => route('admin.department.select', $redirectParameter)
            ];

            foreach ($checkKeys as $key) {
                /* Check if a route to set this key exists */
                if (! array_key_exists($key, $selectRoutes)) {
                    continue;
                }

                /* return redirect response if key is not set in session */
                if (! session()->has($sessionKeys[$key])) {
                    return redirect($selectRoutes[$key]);
                }
            }
            return true;
        }

        /**
         * Check if a user has only student role
         *
         * @param \App\Models\User $user  (optional) Defaults to current user
         * @return boolean
         */
        public static function isStudentOnly(\App\Models\User $user = null) {
            $user = $user ?: Auth::user();
            return $user->hasRole('student') && ($user->roles->count() == 1);
        }

        /**
         * Get a random string according to the rules specified
         *
         * @param int $len  Length of random string
         * @param int $mixMode  Conditions. String will only contain characters according to the mode
         * @return string
         */
        public static function getRandomStr(int $len = 8, int $mixMode = 6) {
            $numArr = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
            $charArr = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L',
                'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z',
                'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n',
                'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z'];
            $specialArr = ['!', '@', '#', '$', '%', '^', '&', '*', '(', ')', '-', '_',
                '+', '=', '{', '}', '[', ']', ':', ';', '<', '>', '.', '?', '/', '|', '~'];
            $tmpArr = [];
            $randomStr = '';

            switch ($mixMode) {
                case 6: $tmpArr = array_merge($numArr, $charArr); break;
                case 7: $tmpArr = array_merge($numArr, $charArr, $specialArr); break;
                case 4: $tmpArr = $numArr; break;
                case 2: $tmpArr = $charArr; break;
                case 5: $tmpArr = array_merge($numArr, $specialArr); break;
                case 3: $tmpArr = array_merge($charArr, $specialArr); break;
                case 1: $tmpArr = $specialArr;
                default: $tmpArr = array_merge($numArr, $charArr, $specialArr); break;
            }

            shuffle($tmpArr);
            $arrMaxLen = count($tmpArr) - 1;

            for ($i = 0; $i < $len; $i++) {
                $randomStr .= $tmpArr[mt_rand(0, $arrMaxLen)];
            }

            return $randomStr;
        }

        public static function test() {
            return 'Test';
        }
    }
?>
