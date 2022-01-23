<?php

namespace App\Http\Controllers\Profiles;

use App\Helpers\Collections\Users\UserCollection;
use App\Helpers\Collections\Users\UserFollowArray;
use App\Http\Controllers\Controller;
use App\Models\Masters\User;
use App\Models\Masters\UserFollow;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{

    protected $title = "Pengaturan Akun";
    protected $viewPath = "profiles";

    protected $breadcrumbs = [
        ['label' => 'Pengaturan Akun', 'active' => true],
    ];

    protected $user;

    protected $userFollow;

    public function __construct()
    {
        $this->user = new User();
        $this->userFollow = new UserFollow();
    }

    public function index()
    {
        try {

            $query = $this->user->defaultQuery()
                ->with([
                    'followers' => function($query) {
                        UserFollow::foreignWith($query)
                            ->addSelect('user_id');
                    },
                    'followings' => function($query) {
                        UserFollow::foreignWith($query)
                            ->addSelect('user_follow_id');
                    }
                ])
                ->find(auth()->id());

            $user = new UserCollection($query);

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

    public function following()
    {
        try {
            $this->title = "Mengikuti";

            $query = $this->userFollow->where('user_follow_id', auth()->id())
                ->with([
                    'user_following' => function($query) {
                        /* @var Relation $query */
                        $query->select('id', 'full_name', 'user_name', DBImage());
                    }
                ])
                ->get();

            $following = new UserFollowArray($query);

            return response()->json([
                'content' => $this->viewResponse('profile-data-following', [
                    'following' => $following,
                ])
            ]);
        } catch (\Exception $e) {
            return $this->jsonError($e);
        }
    }

    public function follower()
    {
        try {
            $this->title = "Pengikut";

            $query = $this->userFollow->where('user_id', auth()->id())
                ->with([
                    'user_follower' => function($query) {
                        /* @var Relation $query */
                        $query->select('id', 'full_name', 'user_name', DBImage());
                    }
                ])
                ->get();

            $follower = new UserFollowArray($query);

            return response()->json([
                'content' => $this->viewResponse('profile-data-follower', [
                    'follower' => $follower,
                ])
            ]);
        } catch (\Exception $e) {
            return $this->jsonError($e);
        }
    }

    protected function changeImageProfile(Request $req)
    {
        try {

            $file = $req->file('profile');
            $filename = sprintf("%s.%s", date('YmdHis'), $file->getClientOriginalExtension());
            if(!$file->move(storage_path('app/profile'), $filename))
                throw new \Exception("Gagal mengupload foto", \DBCodes::authorizedError);

            $user = $this->user->find(auth()->id());

            if(is_null($user))
                throw new \Exception(\DBMessages::corruptData, \DBCodes::authorizedError);

            $user->update(['profile' => "app/profile/$filename"]);

            return $this->jsonSuccess(\DBMessages::success);
        } catch (\Exception $e) {
            return $this->jsonError($e);
        }
    }
}
