<?php

namespace App\Http\Controllers\Users;

use App\Helpers\Collections\Achievements\AchievementCollection;
use App\Http\Controllers\Controller;
use App\Models\Achievements\Achievement;
use App\Models\Achievements\AchievementTask;
use App\Models\Masters\User;
use App\Models\Users\UserAchievement;
use App\Models\Users\UserAchievementTask;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserAchievementController extends Controller
{

    protected $viewPath = "pages.achievements";
    protected $title = "Achievement Pengguna";

    /* @var User|Relation */
    protected $user;

    /* @var Achievement|Relation */
    protected $achievement;

    /* @var UserAchievement|Relation */
    protected $userAchievement;

    public function __construct()
    {
        $this->user = new User();
        $this->achievement = new Achievement();
        $this->userAchievement = new UserAchievement();
    }

    public function index(Request $req)
    {
        try {

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

            $achievement = $query->map(function($data) {
                return new AchievementCollection($data);
            })->toArray();

            return $this->view('achievement', [
                'achievements' => $achievement
            ]);
        } catch (\Exception $e) {
            return $this->jsonError($e);
        }
    }

    public function share($userid, Request $req)
    {
        try {
            if(!$req->has('id'))
                return redirect(url('/'));

            $achievementId = $req->get('id');

            $user = $this->user->find($userid);

            if(is_null($user))
                return redirect(url('/'));

            $query = $this->achievement->defaultWith($this->achievement->defaultSelects)
                ->with([
                    'user_achievement' => function($query) use ($userid) {
                        /* @var Relation $query */
                        $query->select('id', 'achievement_id', 'percentage')
                            ->where('user_id', $userid);
                    },
                    'tasks' => function($query) use ($userid) {
                        AchievementTask::foreignWith($query)
                            ->addSelect('achievement_id', 'payload', DB::raw('(
                                SELECT usr_task.payload as user_payload
                                FROM usr_achievement_task usr_task
                                JOIN usr_achievement usr_achievement ON usr_achievement.id = usr_task.user_achievement_id
                                WHERE usr_task.task_type_id = ms_achievement_task.task_type_id
                                    AND usr_achievement.user_id = ' . $userid . '
                                    AND usr_achievement.achievement_id = ms_achievement_task.achievement_id
                            ) as user_payload'));
                    }
                ])
                ->addSelect(DBImage('preview', 'image'))
                ->orderBy('sequence')
                ->find($achievementId);

            if(is_null($query))
                return redirect(url('/'));

            $achievement = new AchievementCollection($query);

            return $this->view('share-achievement', [
                'achievement' => $achievement
            ]);
        } catch (\Exception $e) {
            return $this->jsonError($e);
        }
    }
}
