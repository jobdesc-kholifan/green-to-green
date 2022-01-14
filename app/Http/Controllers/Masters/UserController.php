<?php

namespace App\Http\Controllers\Masters;

use App\Helpers\Collections\Users\UserCollection;
use App\Http\Controllers\Controller;
use App\Models\Masters\User;
use App\View\Components\Button;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    protected $viewPath = "users";
    protected $title = "Users";
    protected $route = [\DBRoutes::user];

    protected $breadcrumbs = [
        ['label' => 'Users'],
    ];

    /* @var User|Relation */
    protected $user;

    public function __construct()
    {
        $this->user = new User();
    }

    public function index()
    {
        try {

            return $this->view('user');
        } catch (\Exception $e) {
            return $this->jsonError($e);
        }
    }

    public function datatables()
    {
        try {
            $query = $this->user->defaultQuery()
                ->where('id', '!=', auth()->id());

            return datatables()->eloquent($query)
                ->editColumn('gender', function($data) {
                    return ['config_name' => !is_null($data->gender) ? $data->gender->config_name : ''];
                })
                ->addColumn('ttl', function($data) {
                    return sprintf("%s, %s", $data->place_of_birth, $data->date_of_birth);
                })
                ->addColumn('action', function($data) {

                    $btnEdit = (new Button("actions.edit($data->id)", Button::btnPrimary, Button::btnIconEdit))
                        ->render();

                    $btnDelete = (new Button("actions.delete($data->id)", Button::btnDanger, Button::btnIconDelete))
                        ->render();

                    $btnDetail = (new Button("actions.detail($data->id)", Button::btnPrimary, Button::btnIconInfo))
                        ->setLabel("Lihat Detail")
                        ->render();

                    return \DBText::renderAction([$btnDetail, $btnEdit, $btnDelete]);
                })
                ->toJson();
        } catch (\Exception $e) {
            return $this->jsonError($e);
        }
    }

    public function form()
    {
        try {
            $config = findConfig()->parentIn(\DBTypes::gender);

            return response()->json([
                'content' => $this->viewResponse('user-form', [
                    'genders' => $config->children(\DBTypes::gender),
                ]),
            ]);
        } catch (\Exception $e) {
            return $this->jsonError($e);
        }
    }

    public function store(Request $req)
    {
        try {

            $insertUser = collect($req->only($this->user->getFillable()))
                ->merge([
                    'user_password' => Hash::make($req->get('password')),
                    'date_of_birth' => dbDate($req->get('date_of_birth')),
                ]);
            $this->user->create($insertUser->toArray());

            return $this->jsonSuccess(\DBMessages::successCreate);
        } catch (\Exception $e) {
            return $this->jsonError($e);
        }
    }

    public function show($id)
    {
        try {
            $row = $this->user->defaultQuery()
                ->find($id);

            if(is_null($row))
                throw new \Exception(\DBMessages::corruptData, \DBCodes::authorizedError);

            return $this->jsonData($row);
        } catch (\Exception $e) {
            return $this->jsonError($e);
        }
    }

    public function update(Request $req, $id)
    {
        try {
            $row = $this->user->find($id);

            if(is_null($row))
                throw new \Exception(\DBMessages::corruptData, \DBCodes::authorizedError);

            $updateUser = collect($req->only($this->user->getFillable()))
                ->merge([
                    'date_of_birth' => dbDate($req->get('date_of_birth'))
                ]);
            if(!$req->has('password') && !empty($req->get('password')))
                $updateUser->merge(['user_password' => Hash::make($req->get('password'))]);

            $row->update($updateUser->toArray());

            return $this->jsonData($row);
        } catch (\Exception $e) {
            return $this->jsonError($e);
        }
    }

    public function destroy($id)
    {
        try {
            $row = $this->user->find($id);
            $row->delete();

            return $this->jsonData($row);
        } catch (\Exception $e) {
            return $this->jsonError($e);
        }
    }

    public function detail(Request $req)
    {
        try {
            $row = $this->user->defaultQuery()
                ->find($req->get('id'));

            if(is_null($row))
                throw new \Exception(\DBMessages::corruptData, \DBCodes::authorizedError);

            $user = new UserCollection($row);

            return response()->json([
                'content' => $this->viewResponse('user-detail', [
                    'user' => $user
                ]),
            ]);
        } catch (\Exception $e) {
            return $this->jsonError($e);
        }
    }

    public function check(Request $req)
    {
        try {
            $label = $req->get('label');
            $field = $req->get('field');
            $value = $req->get('value');

            $query = $this->user->select($field)
                ->where($field, $value);

            if($req->has('id')) {
                $query->where('id', '!=', $req->get('id'));
            }

            $user = $query->first();

            if(!is_null($user))
                throw new \Exception(sprintf(\DBMessages::existData, ucfirst(strtolower($label)), $value), \DBCodes::authorizedError);

            return $this->jsonSuccess(\DBMessages::success);
        } catch (\Exception $e) {
            return $this->jsonError($e);
        }
    }
}
