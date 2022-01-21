<?php

namespace App\Http\Controllers\Profiles;

use App\Helpers\Collections\Users\UserCollection;
use App\Http\Controllers\Controller;

class ProfileController extends Controller
{

    protected $title = "Profile";
    protected $viewPath = "profiles";

    protected $breadcrumbs = [
        ['label' => 'Profile', 'active' => true],
    ];

    public function index()
    {
        try {

            $user = UserCollection::current();

            return $this->view('profile', [
                'user' => $user,
                'active' => 'datadiri'
            ]);
        } catch (\Exception $e) {
            return $this->jsonError($e);
        }
    }
}
