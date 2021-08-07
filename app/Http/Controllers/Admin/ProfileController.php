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
use App\Traits\StoreFiles;

class ProfileController extends Controller {
    use StoreFiles;

    /**
     * Items per page
     *
     * @var int
     */
    private $paginate = 12;

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
        $this->authorize('view', Profile::class);

        $profiles = Profile::with('department')->paginate($this->paginate);

        $ownProfile = false;
        if (Auth::user()->hasProfile()) {
            $ownProfile = Auth::user()->profileLink->profile_id;
        }

        return view('admin.profiles.show', [
            'profiles' => $profiles->toArray(),
            'pagination' => $profiles->links('vendor.pagination.default'),
            'ownProfile' => $ownProfile
        ]);
    }

    public function add() {
        $this->authorize('create', Profile::class);

        $user = Auth::user();
        $canChooseType = $user->can('chooseType', Profile::class);
        $canCustomizeLink = $user->can('customizeLinkOption', Profile::class);
        $departments = Department::all();

        $type = '';
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
        return redirect()->route('admin.profiles.show')->with([
            'status' => 'success',
            'message' => $msg
        ]);
    }

    /**
     * Type conversion required to 'int' as profile policy does strict comparison
     *
     * @param int $id
     */
    public function edit(int $id) {
        $this->authorize('update', [Profile::class, $id]);

        $profile = Profile::withTrashed()->with(['department:id,name', 'userLink'])->findOrFail($id);
        $user = Auth::user();
        $canChooseType = $user->can('chooseType', Profile::class);
        $canCustomizeLink = $user->can('customizeLinkOption', Profile::class);
        $departments = Department::all();

        return view('admin.profiles.edit', [
            'profile' => $profile,
            'canChooseType' => $canChooseType,
            'canCustomizeLink' => $canCustomizeLink,
            'departments' => $departments
        ]);
    }

    public function update(Request $request, int $id) {
        $this->authorize('update', [Profile::class, $id]);

        $profile = Profile::withTrashed()->findOrFail($id);

        $data = $request->validate([
            'name' => 'required | string | min:3',
            'designation' => 'required | string',
            'email' => ['required', 'email',
                Rule::unique('profiles', 'email')->ignore($profile->id)
            ],
            'mobile' => ['required', 'numeric', 'digits:10',
                Rule::unique('profiles', 'mobile')->ignore($profile->id)
            ],
            'type' => 'required | in:faculty,staff',
            'department_id' => ['required',
                Rule::exists('departments', 'id')
            ],
            'remove_profile_image' => 'filled | in:on',
            'profile_image' => 'filled | image | max:800',
            'work_experience' => 'nullable',
            'academic_qualifications' => 'nullable',
            'office_address' => 'nullable',
            'areas_of_interest' => 'nullable',
            'teachings' => 'nullable',
            'publications' => 'nullable'
        ]);

        $user = Auth::user();
        try {
            $profile->update($data);

            if (isset($data['remove_profile_image'])) {
                $this->removeProfileImage($profile->image);
                $profile->image = null;
            } else if ($request->hasFile('profile_image') && $request->file('profile_image')->isValid()) {
                $this->removeProfileImage($profile->image);
                $profile->image = $this->storeProfileImage($request->file('profile_image'), $profile->id);
            }

            if ($user->can('chooseType', Profile::class)) {
                $profile->type = $data['type'];
            }
            if ($user->can('updateDepartment', Profile::class)) {
                $profile->department_id = $data['department_id'];
            }
            $profile->save();
        } catch (\Exception $e) {
            return back()->with([
                'status' => 'fail',
                'message' => 'Failed to update profile!'
            ])->withInput();
        }

        return back()->with([
            'status' =>'success',
            'message' => 'Profile updated'
        ]);
    }

    /**
     * Returns profile type to be created according to user role
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
