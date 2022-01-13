<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\Collections\Users\UserCollection;
use App\Http\Controllers\Controller;
use App\Models\Masters\Config;
use App\Models\Masters\User;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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

    public function login()
    {
        try {
            return $this->view('login');
        } catch (\Exception $e) {
            return $this->jsonError($e);
        }
    }

    public function processLogin(Request $req)
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

    public function processGoogleLogin(Request $req)
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

            $config = findConfig()->in([\DBTypes::roleUser, \DBTypes::statusActive]);

            $password = $req->get('password');
            $insertUser = collect($req->only($this->user->getFillable()))
                ->merge([
                    'user_password' => Hash::make($password),
                    'role_id' => $config->get(\DBTypes::roleUser)->getId(),
                    'status_id' => $config->get(\DBTypes::statusActive)->getId(),
                ]);
            $user = UserCollection::create($insertUser->toArray());

            $credentials = [
                'user_name' => $user->getUserName(),
                'password' => $password,
            ];

            if(!Auth::attempt($credentials))
                throw new \Exception(\DBMessages::loginFailed, \DBCodes::authorizedError);

            return $this->jsonData([
                'redirect' => url('/')
            ]);
        } catch (\Exception $e) {
            return $this->jsonError($e);
        }
    }
}
