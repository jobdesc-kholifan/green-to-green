<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\Collections\Achievements\TasksRegisterPayload;
use App\Helpers\Collections\Users\UserCollection;
use App\Helpers\UserAchievement\CreateActivity;
use App\Http\Controllers\Controller;
use App\Models\Masters\Config;
use App\Models\Masters\User;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{

    protected $guardsAdmin = [\DBTypes::roleSuperuser,\DBTypes::roleAdministrator];

    protected $title = "Login";
    protected $viewPath = "auth";

    protected $breadcrumbs = [];

    /* @var User|Relation */
    protected $user;

    public function __construct()
    {
        $this->user = new User();
    }

    public function signIn()
    {
        try {
            return $this->view('signup');
        } catch (\Exception $e) {
            return $this->jsonError($e);
        }
    }

    public function processSignIn(Request $req)
    {
        try {

            $username = $req->get('username');
            $password = $req->get('password');

            $credentials = ['user_name' => $username, 'password' => $password];

            $checkEmail = $this->user->defaultWith(['user_name', 'role_id', 'status_id'])
                ->with([
                    'role' => function($query) {
                        Config::foreignWith($query);
                    },
                    'status' => function($query) {
                        Config::foreignWith($query);
                    }
                ])
                ->where('email', $username)
                ->first();

            if(!is_null($checkEmail)) {
                $user = new UserCollection($checkEmail);
                $credentials = [
                    'user_name' => $user->getUserName(),
                    'password' => $password,
                ];
            }

            else {
                $row = $this->user->defaultWith(['user_name', 'role_id', 'status_id'])
                    ->with([
                        'role' => function($query) {
                            Config::foreignWith($query);
                        },
                        'status' => function($query) {
                            Config::foreignWith($query);
                        }
                    ])
                    ->where('user_name', $username)
                    ->first();

                $user = new UserCollection($row);
            }

            if(!Auth::attempt($credentials))
                throw new \Exception(\DBMessages::loginFailed, \DBCodes::authorizedError);

            if(in_array($user->getRole()->getSlug(), $this->guardsAdmin))
                if(!Auth::guard('admin')->attempt($credentials))
                    throw new \Exception(\DBMessages::loginFailed, \DBCodes::authorizedError);

            return $this->jsonSuccess(\DBMessages::loginSuccess, [
                'redirect' => url('/'),
            ]);
        } catch (\Exception $e) {
            return $this->jsonError($e);
        }
    }

    public function processGoogleSignIn(Request $req)
    {
        try {
            $email = $req->get('email');
            $checkEmail = $this->user->defaultWith($this->user->defaultSelects)
                ->with([
                    'role' => function($query) {
                        Config::foreignWith($query);
                    },
                    'status' => function($query) {
                        Config::foreignWith($query);
                    }
                ])
                ->where('email', $email)
                ->first();

            if(is_null($checkEmail)) {
                $token = encrypt(implode(":", $req->only($this->user->getFillable())));
                return $this->jsonData([
                    'redirect' => sprintf("%s?token=%s", route(\DBRoutes::authCompleteSignUp), $token),
                ]);
            }

            $user = new UserCollection($checkEmail);

            Auth::login($checkEmail);
            if(in_array($user->getRole()->getSlug(), $this->guardsAdmin))
                Auth::guard('admin')->login($checkEmail);

            return $this->jsonData([
                'redirect' => url('/')
            ]);
        } catch (\Exception $e) {
            return $this->jsonError($e);
        }
    }

    public function processTwitterSignIn(Request $req)
    {
        return Socialite::driver('twitter')->redirect();
    }

    public function callbackTwitter()
    {

    }

    public function completeSignUp(Request $req)
    {
        try {
            list($fullName, $email) = explode(":", decrypt($req->get('token')));

            return $this->view('complete-signup', [
                'email' => $email,
                'fullName' => $fullName,
            ]);
        } catch (\Exception $e) {
            return $this->jsonError($e);
        }
    }

    public function processCompleteSignUp(Request $req)
    {
        try {

            $config = findConfig()->in([\DBTypes::roleCustomer, \DBTypes::statusActive]);

            $password = $req->get('password');
            $insertUser = collect($req->only($this->user->getFillable()))
                ->merge([
                    'user_password' => Hash::make($password),
                    'role_id' => $config->get(\DBTypes::roleCustomer)->getId(),
                    'status_id' => $config->get(\DBTypes::statusActive)->getId(),
                ]);
            $user = $this->user->create($insertUser->toArray());

            Auth::login($user);

            $configs = findConfig()->in([\DBTypes::tasksRegister]);

            $registerPayload = new TasksRegisterPayload();
            $registerPayload->setCount(1);

            $activity = new CreateActivity(UserCollection::current());
            $activity->setTaskType($configs->get(\DBTypes::tasksRegister));
            $activity->setTaskPayload($registerPayload->createPayload());
            $activity->run();

            $activity->updatePoints();

            return $this->jsonData([
                'redirect' => url('/')
            ]);
        } catch (\Exception $e) {
            return $this->jsonError($e);
        }
    }

    public function skipSignUp(Request $req)
    {
        try {

            DB::beginTransaction();

            $config = findConfig()->in([\DBTypes::statusActive, \DBTypes::roleCustomer]);
            $insertUser = collect($req->only($this->user->getFillable()))
                ->merge([
                    'role_id' => $config->get(\DBTypes::roleCustomer)->getId(),
                    'status_id' => $config->get(\DBTypes::statusActive)->getId(),
                ]);

            $user = $this->user->create($insertUser->toArray());

            Auth::login($user);

            $configs = findConfig()->in([\DBTypes::tasksRegister]);

            $registerPayload = new TasksRegisterPayload();
            $registerPayload->setCount(1);

            $activity = new CreateActivity(new UserCollection($user));
            $activity->setTaskType($configs->get(\DBTypes::tasksRegister));
            $activity->setTaskPayload($registerPayload->createPayload());
            $activity->run();

            $activity->updatePoints();

            DB::commit();

            return $this->jsonData([
                'redirect' => url('/')
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->jsonError($e);
        }
    }

    public function signUp()
    {
        try {
            return $this->view('register');
        } catch (\Exception $e) {
            return $this->jsonError($e);
        }
    }

    public function processSignUp(Request $req)
    {
        try {

            DB::beginTransaction();

            $config = findConfig()->in([\DBTypes::statusActive, \DBTypes::roleCustomer]);
            $insertUser = collect($req->only($this->user->getFillable()))
                ->merge([
                    'user_password' => Hash::make($req->get('password')),
                    'role_id' => $config->get(\DBTypes::roleCustomer)->getId(),
                    'status_id' => $config->get(\DBTypes::statusActive)->getId(),
                ]);
            $user = $this->user->create($insertUser->toArray());

            Auth::login($user);

            $configs = findConfig()->in([\DBTypes::tasksRegister]);

            $registerPayload = new TasksRegisterPayload();
            $registerPayload->setCount(1);

            $activity = new CreateActivity(UserCollection::current());
            $activity->setTaskType($configs->get(\DBTypes::tasksRegister));
            $activity->setTaskPayload($registerPayload->createPayload());
            $activity->run();

            $activity->updatePoints();

            DB::commit();

            return $this->jsonData([
                'redirect' => url('/'),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->jsonError($e);
        }
    }
}
