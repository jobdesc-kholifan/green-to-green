<?php

namespace App\Http\Controllers\Profiles;

use App\Http\Controllers\Controller;
use App\Models\Achievements\Achievement;
use App\Models\Masters\User;
use App\Models\Masters\UserFollow;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SearchFriendsController extends Controller
{

    protected $title = "Cari Teman";
    protected $route = [\DBRoutes::searchFriends];
    protected $viewPath = 'profiles';

    protected $breadcrumbs = [
        ['label' => 'Cari Teman', 'active' => true],
    ];

    /**
     * @var User|Relation
     * */
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

            return $this->view('search-friends');
        } catch (\Exception $e) {
            return $this->errorPage($e);
        }
    }

    public function listFriends(Request $req)
    {
        try {
            $searchValue = trim(strtolower($req->get('searchValue')));

            /* @var Relation $query */
            $query = $this->user->select('id', 'user_name', 'full_name', 'bio', DBImage())
                ->with([
                    'is_following' => function($query) {
                        UserFollow::foreignWith($query)
                            ->where('user_id', auth()->id())
                            ->addSelect( 'user_follow_id');
                    },
                    'is_follower' => function($query) {
                        UserFollow::foreignWith($query)
                            ->where('user_follow_id', auth()->id())
                            ->addSelect('user_id', 'user_follow_id');
                    },
                    'user_achievement' => function($query) {
                        /* @var Relation $query */
                        $query->select('user_id', 'achievement_id', 'percentage')
                            ->with([
                                'achievement' => function($query) {
                                    Achievement::foreignWith($query)
                                        ->addSelect(DBImage('preview', 'image'))
                                        ->orderBy('sequence');
                                }
                            ]);
                    }
                ])
                ->whereHas('role', function($query) {
                    /* @var Relation $query */
                    $query->where('slug', \DBTypes::roleCustomer);
                })
                ->where(function($query) use ($searchValue) {
                    /* @var Relation $query */
                    $query->where(DB::raw('TRIM(LOWER(user_name))'), 'like', "%$searchValue%")
                        ->orWhere(DB::raw('TRIM(LOWER(full_name))'), 'like', "%$searchValue%");
                })
                ->where('id', '!=', auth()->id());

            if($req->has('random')) {
                $following = $this->userFollow->where('user_id', auth()->id())
                    ->count();

                if($following > 0) {
                    $query->whereRaw('id IN (
                        SELECT suggest.user_follow_id
                        FROM usr_follow suggest
                        WHERE suggest.user_id IN (
                            SELECT usr_follow.user_follow_id
                            FROM usr_follow
                            WHERE user_id = ' . auth()->id() . '
                        )
                        AND suggest.user_follow_id NOT IN (
                            SELECT resource.user_follow_id
                            FROM usr_follow resource
                            WHERE resource.user_id = '.auth()->id().'
                        )
                    )');
                }
            }

            return $this->jsonData($query->get());
        } catch (\Exception $e) {
            return $this->jsonError($e);
        }
    }

    public function followFriends(Request $req)
    {
        try {

            $insertFollow = collect($req->only($this->userFollow->getFillable()));
            $this->userFollow->create($insertFollow->toArray());

            return $this->jsonSuccess(\DBMessages::success);
        } catch (\Exception $e) {
            return $this->jsonError($e);
        }
    }
}
