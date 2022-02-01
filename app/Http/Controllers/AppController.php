<?php

namespace App\Http\Controllers;

use App\Helpers\Collections\Orders\OrderCollection;
use App\Models\Masters\User;
use App\Models\Orders\Order;
use App\View\Components\Button;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Request;

class AppController extends Controller
{

    protected $title = "Welcome to Green to Green";

    protected $order;

    public function __construct()
    {
        $this->order = new Order();
    }

    public function index()
    {
        try {

            $configs = findConfig()->parentIn([\DBTypes::statusOrder, \DBTypes::rubbishCategory]);
            return $this->view('home', [
                'statues' => $configs->children(\DBTypes::statusOrder),
                'categoryRubbish' => $configs->children(\DBTypes::rubbishCategory),
            ]);
        } catch (\Exception $e) {
            return $this->jsonError($e);
        }
    }

    public function datatablesOrder(Request $req)
    {
        try {
            $query = $this->order->defaultQuery()
                ->addSelect('tr_order.created_at')
                ->where('user_id', auth()->id());

            if($req->has('filter_category')) {
                $filterCategory = collect(explode(",", $req->get('filter_category')))->filter(function($data) {
                    return $data != '';
                });
                if($filterCategory->count() > 0)
                    $query->whereHas('rubbish', function($query) use ($filterCategory) {
                        /* @var Relation $query */
                        $query->whereIn('category_id', $filterCategory->toArray());
                    });
            }

            if($req->has('filter_status')) {
                $filterStatus = collect(explode(",", $req->get('filter_status')))->filter(function($data) {
                    return $data != '';
                });
                if ($filterStatus->count() > 0)
                    $query->whereIn('status_id', $filterStatus->toArray());
            }

            return datatables()->eloquent($query)
                ->editColumn('created_at', function($data) {
                    return dbDate($data->created_at, 'd F Y');
                })
                ->editColumn('address', function($data) {
                    return sprintf("%s ...", substr($data->address,0, 50));
                })
                ->addColumn('action', function($data) {
                    $btnDetail = (new Button("actions.detail($data->id)", Button::btnOlive, Button::btnIconInfo))
                        ->setLabel("Lihat Detail")
                        ->render();

                    return \DBText::renderAction([$btnDetail]);
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
                'content' => $this->viewResponse('orders.order-detail', [
                    'order' => $order,
                ]),
            ]);
        } catch (\Exception $e) {
            return $this->jsonError($e);
        }
    }

    public function admin()
    {
        try {
            return $this->view('admin');
        } catch (\Exception $e) {
            return $this->jsonError($e);
        }
    }
}
