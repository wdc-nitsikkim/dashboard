<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

use App\CustomHelper;
use App\Models\Profile;
use App\Models\Department;
use App\Models\UserProfileLink;

class ProfileController extends Controller {
    /**
     * Items per page
     *
     * @var int
     */
    private $paginate = 5;

    /**
     * Stores session keys received from \CustomHelper::getSessionConstants()
     *
     * @var null|array
     */
    private $sessionKeys = null;

    function __construct() {
        $this->sessionKeys = CustomHelper::getSessionConstants();
    }

    public function show() {
        return 'All profiles in table';
    }

    public function add() {
        $this->authorize('create', Profile::class);

        $user = Auth::user();
        $canChooseType = $user->can('chooseType', Profile::class);
        $canCustomizeLink = $user->can('customizeLinkOption', Profile::class);
        $departments = Department::all();

        $type= '';
        if ($user->hasRole('hod', 'faculty')) {
            $type = 'faculty';
        } else if ($user->hasRole('staff')) {
            $type = 'staff';
        }

        return view('admin.profiles.add', [
            'canChooseType' => $canChooseType,
            'canCustomizeLink' => $canCustomizeLink,
            'departments' => $departments,
            'userType' => $type
        ]);
    }

    public function saveNew(Request $request) {
        $this->authorize('create', Profile::class);

        $data = $request->validate([
            'name' => 'required | string | min:3',
            'designation' => 'required | string',
            'email' => ['required', 'email',
                Rule::unique('profiles', 'email')
            ],
            'mobile' => ['required', 'numeric', 'digits:10',
                Rule::unique('profiles', 'mobile')
            ],
            'type' => 'required | in:faculty,staff',
            'department_id' => ['required',
                Rule::exists('departments', 'id')
            ],
            'link_account' => 'filled | in:on'
        ]);

        $user = Auth::user();
        $link = true;
        $data = collect($data);
        $extract = ['name', 'designation', 'email', 'mobile'];
        try {
            $profile = new Profile($data->only($extract)->toArray());
            $profile->department_id = $data['department_id'];
            $profile->type = $this->getProfileType($user, $data['type']);
            $profile->save();

            $msg = 'Profile created!';
            if ($user->can('customizeLinkOption', Profile::class) && !isset($data['link_account'])) {
                $link = false;
            }
            if ($user->hasProfile()) {
                $link = false;
                $msg = 'Linking declined as another profile is already linked!';
            }

            if ($link) {
                UserProfileLink::create([
                    'user_id' => $user->id,
                    'profile_id' => $profile->id
                ]);
                $msg = 'Profile created & linked!';
            }
        } catch (\Exception $e) {
            return back()->with([
                'status' => 'fail',
                'message' => 'Failed to create profile!'
            ])->withInput();
        }
        return redirect('/default')->with([
            'status' => 'success',
            'message' => $msg
        ]);
    }

    /**
     * Gets profile type to be created according to user role
     *
     * @param App\Models\User $user
     * @param string $chosen
     * @return string
     */
    private function getProfileType(\App\Models\User $user, $chosen) {
        if ($user->can('chooseType', Profile::class)) {
            return $chosen;
        } else if ($user->hasRole('hod', 'faculty')) {
            return 'faculty';
        }
        return 'staff';
    }

    public function test() {
        return 'Test';
    }
}
