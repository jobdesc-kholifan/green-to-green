<?php

namespace App\Http\Controllers\Users;

use App\Helpers\Collections\Achievements\TasksCollectPlasticPayload;
use App\Helpers\Collections\Achievements\TasksCreateRequestPayload;
use App\Helpers\Collections\Users\UserCollection;
use App\Helpers\UserAchievement\CreateActivity;
use App\Http\Controllers\Controller;
use App\Models\Orders\Order;
use App\Models\Orders\OrderRubbish;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RequestPickUpController extends Controller
{

    protected $viewPath = "pages.pickup";
    protected $title = "Pemesanan Pickup";

    /* @var Order|Relation */
    protected $order;

    /* @var OrderRubbish|Relation */
    protected $orderRubbish;

    public function __construct()
    {
        $this->order = new Order();
        $this->orderRubbish = new OrderRubbish();
    }

    public function index()
    {
        try {
            return $this->view('pickup');
        } catch (\Exception $e) {
            return $this->jsonError($e);
        }
    }

    public function maps()
    {
        try {

            return response()->json([
                'content' => $this->viewResponse('maps'),
            ]);
        } catch (\Exception $e) {
            return $this->jsonError($e);
        }
    }

    public function store(Request $req)
    {
        try {

            DB::beginTransaction();

            $configs = findConfig()->in([\DBTypes::statusOrderNew, \DBTypes::statusActive, \DBTypes::tasksCreatePickup, \DBTypes::tasksCollectPlastic]);

            $insertOrder = collect($req->only($this->order->getFillable()))
                ->merge([
                    'user_id' => auth()->id(),
                    'status_id' => $configs->get(\DBTypes::statusOrderNew)->getId(),
                ]);
            $order = $this->order->create($insertOrder->toArray());

            $payloadCollectPlastic = [];

            $order_detail = json_decode($req->get('order_detail'), '[]');
            foreach($order_detail as $orderDetail) {
                $orderDetail = (object) $orderDetail;

                $this->orderRubbish->create([
                    'order_id' => $order->id,
                    'category_id' => $orderDetail->category_id,
                    'qty' => $orderDetail->qty,
                    'status_id' => $configs->get(\DBTypes::statusActive)->getId(),
                ]);

                $payload = new TasksCollectPlasticPayload();
                $payload->setCount($orderDetail->qty);
                $payload->setCategoryId((object) ['value' => $orderDetail->category_id]);

                $payloadCollectPlastic[] = $payload->createPayload();
            }

            $payloadCreateRequest = new TasksCreateRequestPayload();
            $payloadCreateRequest->setCount(1);

            $user = UserCollection::current();

            $activity = new CreateActivity($user);
            $activity->setTaskType($configs->get(\DBTypes::tasksCreatePickup));
            $activity->setTaskPayload($payloadCreateRequest->createPayload());
            $activity->run();

            $activity->setTaskType($configs->get(\DBTypes::tasksCollectPlastic));
            $activity->setTaskPayload($payloadCollectPlastic);
            $activity->run();

            $activity->updatePoints();

            DB::commit();

            return $this->jsonSuccess(\DBMessages::successCreate);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->jsonError($e);
        }
    }
}
