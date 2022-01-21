<?php

namespace App\Models\Orders;

use App\Models\Masters\Config;
use App\Models\Masters\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;

class Order extends Model
{
    use HasFactory;

    protected $table = "tr_order";

    protected $fillable = [
        'user_id',
        'lat_lng',
        'address',
        'driver_note',
        'status_id',
    ];

    public $defaultSelects = [
        'lat_lng',
        'tr_order.address',
        'driver_note',
    ];

    /**
     * static function yang digunakan ketika memanggil with biar tidak perlu
     * dituliskan lagi
     *
     * @param Relation $query
     * @param array $selects
     *
     * @return Relation
     * */
    static public function foreignWith($query, $selects = null)
    {
        $model = new Order();
        return $model->defaultWith(is_null($selects) ? $model->defaultSelects : $selects, $query);
    }

    /**
     * function untuk setting default with apa saja yang akan sering dipakai
     * tetapi jangan banyak-banyak karena akan memperngaruhi proses loading page
     *
     * @param Relation|Order $query
     * @param array $selects
     *
     * @return Relation
     * */
    private function _defaultWith($query, $selects = [])
    {
        return $query->with([
        ])->select(sprintf("%s.%s", $this->getTable(), $this->getKeyName()))->addSelect($selects);
    }

    /**
     * function defaultWith yang digunakan untuk dipanggil public
     *
     * @param array $selects
     * @param Relation|Order|null $query
     *
     * @return Relation
     * */
    public function defaultWith($selects = [], $query = null)
    {
        return $this->_defaultWith(is_null($query) ? $this : $query, $selects);
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function status()
    {
        return $this->hasOne(Config::class, 'id', 'status_id');
    }

    public function schedule()
    {
        return $this->hasOne(OrderSchedule::class, 'id', 'schedule_id');
    }

    public function defaultQuery()
    {
        return $this->defaultWith($this->defaultSelects)
            ->with([
                'user' => function($query) {
                    User::foreignWith($query);
                },
                'status' => function($query) {
                    Config::foreignWith($query);
                },
                'schedule' => function($query) {
                    OrderSchedule::foreignWith($query)
                        ->with([
                            'staff' => function($query) {
                                /* @var Relation $query */
                                $query->select('id', 'full_name', 'phone_number');
                            }
                        ])
                        ->addSelect('staff_id');
                }
            ])
            ->addSelect('user_id', 'tr_order.status_id', 'schedule_id');
    }
}
