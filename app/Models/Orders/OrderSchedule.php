<?php

namespace App\Models\Orders;

use App\Models\Masters\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;

class OrderSchedule extends Model
{
    use HasFactory;

    protected $table = "tr_order_schedule";

    protected $fillable = [
        'order_id',
        'schedule_date',
        'staff_id',
        'schedule_id',
        'description',
    ];

    public $defaultSelects = [
        'schedule_date',
        'description',
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
        $model = new OrderSchedule();
        return $model->defaultWith(is_null($selects) ? $model->defaultSelects : $selects, $query);
    }

    /**
     * function untuk setting default with apa saja yang akan sering dipakai
     * tetapi jangan banyak-banyak karena akan memperngaruhi proses loading page
     *
     * @param Relation|OrderSchedule $query
     * @param array $selects
     *
     * @return Relation
     * */
    private function _defaultWith($query, $selects = [])
    {
        return $query->with([
        ])->select($this->getKeyName())->addSelect($selects);
    }

    /**
     * function defaultWith yang digunakan untuk dipanggil public
     *
     * @param array $selects
     * @param Relation|OrderSchedule|null $query
     *
     * @return Relation
     * */
    public function defaultWith($selects = [], $query = null)
    {
        return $this->_defaultWith(is_null($query) ? $this : $query, $selects);
    }

    public function staff()
    {
        return $this->hasOne(User::class, 'id', 'staff_id');
    }

    public function schedule()
    {
        return $this->hasOne(OrderSchedule::class, 'id', 'schedule_id');
    }
}
