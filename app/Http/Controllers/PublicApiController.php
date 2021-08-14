<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Profile;

class PublicApiController extends Controller {
    private $maxResults = 10;

    public function searchUsersByName(Request $request) {
        $request->validate([
            'name' => 'required | min:1'
        ]);

        $name = '%' . $request->name . '%';
        $users = User::select('id', 'name')->where('name', 'like', $name)
            ->take($this->maxResults)->get()->toJson();
        return $users;
    }

    public function searchProfilesByName(Request $request) {
        $request->validate([
            'name' => 'required | min:1'
        ]);

        $name = '%' . $request->name . '%';
        $profiles = Profile::select('id', 'name')->where('name', 'like', $name)
            ->take($this->maxResults)->get()->toJson();
        return $profiles;
    }
}
