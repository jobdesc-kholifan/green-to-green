<?php

namespace App\Http\Controllers\Profiles;

use App\Helpers\Collections\Achievements\AchievementCollection;
use App\Helpers\Collections\Users\UserCollection;
use App\Helpers\Collections\Users\UserFollowArray;
use App\Http\Controllers\Controller;
use App\Models\Achievements\Achievement;
use App\Models\Achievements\AchievementTask;
use App\Models\Masters\User;
use App\Models\Masters\UserFollow;
use App\Models\Users\UserAchievement;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

    protected $achievement;

    public function __construct()
    {
        $this->user = new User();
        $this->userFollow = new UserFollow();
        $this->achievement = new Achievement();
    }

    public function index()
    {
        try {

            $query = $this->user->defaultQuery()
                ->with([
                    'followers' => function($query) {
                        UserFollow::foreignWith($query)
                            ->addSelect('user_follow_id');
                    },
                    'followings' => function($query) {
                        UserFollow::foreignWith($query)
                            ->addSelect('user_id');
                    },
                    'user_achievements' => function($query) {
                        UserAchievement::foreignWith($query)
                            ->with([
                                'achievement' => function($query) {
                                    Achievement::foreignWith($query, ['title', DBImage('preview', 'image')]);
                                }
                            ])
                            ->addSelect('user_id', 'achievement_id');
                    }
                ])
                ->find(auth()->id());

            $user = new UserCollection($query);

            $query = $this->achievement->defaultWith($this->achievement->defaultSelects)
                ->with([
                    'user_achievement' => function($query) {
                        /* @var Relation $query */
                        $query->select('id', 'achievement_id', 'percentage')
                            ->where('user_id', auth()->id());
                    },
                    'tasks' => function($query) {
                        AchievementTask::foreignWith($query)
                            ->addSelect('achievement_id', 'payload', DB::raw('(
                                SELECT usr_task.payload as user_payload
                                FROM usr_achievement_task usr_task
                                JOIN usr_achievement usr_achievement ON usr_achievement.id = usr_task.user_achievement_id
                                WHERE usr_task.task_type_id = ms_achievement_task.task_type_id
                                    AND usr_achievement.user_id = ' . auth()->id() . '
                                    AND usr_achievement.achievement_id = ms_achievement_task.achievement_id
                            ) as user_payload'));
                    }
                ])
                ->addSelect(DBImage('preview', 'image'))
                ->orderBy('sequence')
                ->get();

            $achievements = $query->map(function($data) {
                return new AchievementCollection($data);
            })->toArray();

            return $this->view('profile', [
                'user' => $user,
                'achievements' => $achievements,
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
                if(!Hash::check($req->get('old_password'), $user->user_password))
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

            $query = $this->userFollow->where('user_id', auth()->id())
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

            $query = $this->userFollow->where('user_follow_id', auth()->id())
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
