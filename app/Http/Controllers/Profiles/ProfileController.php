<?php

namespace App\Http\Controllers\Profiles;

use App\Helpers\Collections\Users\UserCollection;
use App\Http\Controllers\Controller;
use App\Models\Masters\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{

    protected $title = "Profile";
    protected $viewPath = "profiles";

    protected $breadcrumbs = [
        ['label' => 'Profile', 'active' => true],
    ];

    protected $user;

    public function __construct()
    {
        $this->user = new User();
    }

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

    public function changeProfile()
    {
        try {

            $profile = UserCollection::current();

            $configs = findConfig()->parentIn(\DBTypes::gender);

            return response()->json([
                'content' => $this->viewResponse('change-profile', [
                    'profile' => $profile,
                    'genders' => $configs->children(\DBTypes::gender)
                ])
            ]);
        } catch (\Exception $e) {
            return $this->jsonError($e);
        }
    }

    public function processChangeProfile(Request $req)
    {
        try {

            $user = $this->user->find(auth()->id());

            if(is_null($user))
                throw new \Exception(\DBMessages::corruptData, \DBCodes::authorizedError);

            $updateUser = collect($req->only($this->user->getFillable()))
                ->merge([
                    'date_of_birth' => dbDate($req->get('date_of_birth'))
                ]);
            $user->update($updateUser->toArray());


            return $this->jsonSuccess(\DBMessages::success);
        } catch (\Exception $e) {
            return $this->jsonError($e);
        }
    }

    public function changePassword()
    {
        try {

            $profile = new UserCollection($user = $this->user->find(auth()->id()));

            return response()->json([
                'content' => $this->viewResponse('change-password', [
                    'profile' => $profile,
                ])
            ]);
        } catch (\Exception $e) {
            return $this->jsonError($e);
        }
    }

    public function processChangePassword(Request $req)
    {
        try {

            $user = $this->user->find(auth()->id());

            if(is_null($user))
                throw new \Exception(\DBMessages::corruptData, \DBCodes::authorizedError);

            if($user->user_password != null)
                if(!Hash::check($req->get('new_password'), $user->user_password))
                    throw new \Exception("Kata sandi salah", \DBCodes::authorizedError);

            $user->update([
                'user_password' => Hash::make($req->get('new_password'))
            ]);

            return $this->jsonSuccess(\DBMessages::success);
        } catch (\Exception $e) {
            return $this->jsonError($e);
        }
    }
}
