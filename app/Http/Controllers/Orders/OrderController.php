<?php

namespace App\Http\Controllers\Orders;

use App\Http\Controllers\Controller;
use App\Models\Orders\Order;
use App\View\Components\Button;
use Illuminate\Database\Eloquent\Relations\Relation;

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

    public function __construct()
    {
        $this->order = new Order();
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

                    return \DBText::renderAction([$btnSchedule, $btnDetail]);
                })
                ->toJson();
        } catch (\Exception $e) {
            return $this->jsonError($e);
        }
    }

    public function detail()
    {
        try {
            $this->title = "Order";

            return response()->json([
                'content' => $this->viewResponse('order-detail'),
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
}
