<?php

namespace App\Http\Controllers\Masters;

use App\Http\Controllers\Controller;
use App\Models\Masters\Config;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ConfigController extends Controller
{

    protected $config;

    public function __construct()
    {
        $this->config = new Config();
    }

    public function select(Request $req)
    {
        try {

            $searchValue = trim(strtolower($req->get('term')));
            $query = $this->config->defaultWith($this->config->defaultSelects)
                ->where(function($query) use ($searchValue) {
                    /* @var Relation $query */
                    $query->where(DB::raw('TRIM(LOWER(config_name))'), 'like', "%$searchValue%");
                });

            if($req->has('parent_slug')) {
                $slug = $req->get('parent_slug');
                $query->whereHas('parent', function($query) use ($slug) {
                    /* @var Relation $query */
                    $query->where('slug', $slug);
                });
            }

            $json = [];
            foreach($query->get() as $d)
                $json[] = ['id' => $d->id, 'text' => $d->config_name];

            return response()->json($json);
        } catch (\Exception $e) {
            return $this->jsonError($e);
        }
    }

    public function show($id)
    {
        try {
            $row = $this->config->defaultWith($this->config->defaultSelects)
                ->addSelect('payload')
                ->find($id);

            if(is_null($row))
                throw new \Exception(\DBMessages::corruptData, \DBCodes::authorizedError);

            return $this->jsonData($row);
        } catch (\Exception $e) {
            return $this->jsonError($e);
        }
    }
}
