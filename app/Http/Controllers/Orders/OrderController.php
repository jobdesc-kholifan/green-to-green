<?php

namespace App\Http\Controllers\Orders;

use App\Helpers\Collections\Orders\OrderCollection;
use App\Http\Controllers\Controller;
use App\Models\Orders\Order;
use App\Models\Orders\OrderSchedule;
use App\View\Components\Button;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    protected $title = "Order";
    protected $route = [\DBRoutes::order];
    protected $viewPath = "orders";

    protected $breadcrumbs = [
        ['label' => 'Order', 'active' => true]
    ];

    /**
     * @var Order|Relation
     * */
    protected $order;

    /**
     * @var OrderSchedule|Relation
     * */
    protected $orderSchedule;

    public function __construct()
    {
        $this->order = new Order();
        $this->orderSchedule = new OrderSchedule();
    }

    public function index()
    {
        try {

            return $this->view('order');
        } catch (\Exception $e) {
            return $this->jsonError($e);
        }
    }

    public function datatables()
    {
        try {
            $query = $this->order->defaultQuery();

            return datatables()->eloquent($query)
                ->editColumn('created_at', function($data) {
                    return dbDate($data->created_at, 'd F Y');
                })
                ->editColumn('address', function($data) {
                    return sprintf("%s ...", substr($data->address,0, 50));
                })
                ->editColumn('driver_note', function($data) {
                    return !is_null($data->driver_note) ? sprintf("%s ...", substr($data->driver_note, 0, 50)) : '';
                })
                ->addColumn('action', function($data) {
                    $btnDetail = (new Button("actions.detail($data->id)", Button::btnOlive, Button::btnIconInfo))
                        ->setLabel("Lihat Detail")
                        ->render();

                    $btnSchedule = false;
                    if($data->status->slug == \DBTypes::statusOrderNew)
                        $btnSchedule = (new Button("actions.schedule($data->id)", Button::btnPrimarySolid, Button::btnCalendar))
                            ->render();

                    else if($data->status->slug == \DBTypes::statusOrderInPickup)
                        $btnSchedule = (new Button("actions.done($data->id)", Button::btnPrimarySolid, Button::btnCheckCirlcle))
                            ->setLabel('Selesaikan')
                            ->render();

                    return \DBText::renderAction([$btnSchedule, $btnDetail]);
                })
                ->toJson();
        } catch (\Exception $e) {
            return $this->jsonError($e);
        }
    }

    public function detail(Request $req)
    {
        try {
            $this->title = "Order";

            $query = $this->order->defaultQuery()
                ->find($req->get('id'));

            $order = new OrderCollection($query);

            return response()->json([
                'content' => $this->viewResponse('order-detail', [
                    'order' => $order,
                ]),
            ]);
        } catch (\Exception $e) {
            return $this->jsonError($e);
        }
    }

    public function schedule()
    {
        try {
            $this->title = "Schedule";

            return response()->json([
                'content' => $this->viewResponse('order-schedule'),
            ]);
        } catch (\Exception $e) {
            return $this->jsonError($e);
        }
    }

    public function processSchedule(Request $req)
    {
        try {

            DB::beginTransaction();

            $row = $this->order->find($req->get('id'));

            $configs = findConfig()->in([\DBTypes::statusOrderInPickup]);

            if(is_null($row))
                throw new \Exception(\DBMessages::corruptData, \DBCodes::authorizedError);

            $insertSchedule = collect($req->only($this->orderSchedule->getFillable()))
                ->merge([
                    'order_id' => $row->id,
                    'schedule_date' => dbDate($req->get('schedule_date')),
                ]);
            $schedule = $this->orderSchedule->create($insertSchedule->toArray());

            $row->update([
                'schedule_id' => $schedule->id,
                'status_id' => $configs->get(\DBTypes::statusOrderInPickup)->getId(),
            ]);

            DB::commit();

            return $this->jsonSuccess(\DBMessages::successUpdate);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->jsonError($e);
        }
    }

    public function processDone(Request $req)
    {
        try {

            $row = $this->order->find($req->get('id'));

            $configs = findConfig()->in([\DBTypes::statusOrderDone]);

            if(is_null($row))
                throw new \Exception(\DBMessages::corruptData, \DBCodes::authorizedError);

            $row->update([
                'status_id' => $configs->get(\DBTypes::statusOrderDone)->getId(),
            ]);

            return $this->jsonSuccess(\DBMessages::successUpdate);
        } catch (\Exception $e) {
            return $this->jsonError($e);
        }
    }

    public function form()
    {
        try {

            return response()->json([
                'content' => $this->viewResponse('order-form')
            ]);
        } catch (\Exception $e) {
            return $this->jsonError($e);
        }
    }
}
