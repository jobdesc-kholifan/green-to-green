<?php

namespace App\Http\Controllers\Masters;

use App\Helpers\Collections\Achievements\AchievementCollection;
use App\Helpers\Collections\Achievements\AchievementTaskCollection;
use App\Http\Controllers\Controller;
use App\Models\Achievements\Achievement;
use App\Models\Achievements\AchievementTask;
use App\View\Components\Button;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AchievementController extends Controller
{

    protected $viewPath = "achievement";
    protected $title = "Achievement";
    protected $route = [\DBRoutes::achievement];

    protected $breadcrumbs = [
        ['label' => 'Achievement'],
    ];

    /* @var Achievement|Relation */
    protected $achievement;

    /* @var AchievementTask|Relation */
    protected $achievementTask;

    public function __construct()
    {
        $this->achievement = new Achievement();
        $this->achievementTask = new AchievementTask();
    }

    public function index()
    {
        try {
            return $this->view('achievement');
        } catch (\Exception $e) {
            return $this->jsonError($e);
        }
    }

    public function datatables()
    {
        try {
            $query = $this->achievement->defaultQuery();

            return datatables()->eloquent($query)
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

            return response()->json([
                'content' => $this->viewResponse('achievement-form'),
            ]);
        } catch (\Exception $e) {
            return $this->jsonError($e);
        }
    }

    public function store(Request $req)
    {
        try {

            DB::beginTransaction();

            $config = findConfig()->in([\DBTypes::statusActive]);
            $insertAchievement = collect($req->only($this->achievement->getFillable()))
                ->merge([
                    'status_id' => $config->get(\DBTypes::statusActive)->getId(),
                ]);
            $achievement = AchievementCollection::create($insertAchievement->toArray());

            $tasks = json_decode($req->get('tasks'));
            foreach($tasks as $task) {
                AchievementTaskCollection::create([
                    'achievement_id' => $achievement->getId(),
                    'task_type_id' => $task->task_type->id,
                    'payload' => json_encode($task->payload),
                ]);
            }

            DB::commit();

            return $this->jsonSuccess(\DBMessages::successCreate);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->jsonError($e);
        }
    }

    public function show($id)
    {
        try {
            $row = $this->achievement->defaultQuery()
                ->with([
                    'tasks' => function($query) {
                        AchievementTask::foreignWith($query)
                            ->addSelect('achievement_id');
                    }
                ])
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

            DB::beginTransaction();
            $row = $this->achievement->find($id, ['id']);

            if(is_null($row))
                throw new \Exception(\DBMessages::corruptData, \DBCodes::authorizedError);

            $updateAchievement = collect($req->only($this->achievement->getFillable()));
            $row->update($updateAchievement->toArray());

            $achievement = new AchievementCollection($row);

            $tasks = json_decode($req->get('tasks'));
            foreach($tasks as $task) {
                if(!empty($task->id)) {
                    $row = $this->achievementTask->find($task->id);

                    if(is_null($row))
                        throw new \Exception(\DBMessages::corruptData, \DBCodes::authorizedError);

                    $row->update([
                        'task_type_id' => $task->task_type_id,
                        'payload' => $task->payload,
                    ]);
                }

                else {
                    AchievementTaskCollection::create([
                        'achievement_id' => $achievement->getId(),
                        'task_type_id' => $task->task_type->id,
                        'payload' => json_encode($task->payload),
                    ]);
                }
            }

            DB::commit();

            return $this->jsonSuccess(\DBMessages::successUpdate);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->jsonError($e);
        }
    }

    public function destroy($id)
    {
        try {

            $row = $this->achievement->find($id, ['id']);
            $row->delete();

            return $this->jsonSuccess(\DBMessages::successDelete);
        } catch (\Exception $e) {
            return $this->jsonError($e);
        }
    }

    public function detail(Request $req)
    {
        try {
            $row = $this->achievement->defaultQuery()
                ->with([
                    'tasks' => function($query) {
                        AchievementTask::foreignWith($query)
                            ->addSelect('achievement_id');
                    }
                ])
                ->find($req->get('id'));

            if(is_null($row))
                throw new \Exception(\DBMessages::corruptData, \DBCodes::authorizedError);

            $achievement = new AchievementCollection($row);

            return response()->json([
                'content' => $this->viewResponse('achievement-detail', [
                    'achievement' => $achievement,
                ]),
            ]);
        } catch (\Exception $e) {
            return $this->jsonError($e);
        }
    }
}
