<?php

namespace App\Models\Achievements;

use App\Models\Masters\Config;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\DB;

class AchievementTask extends Model
{
    use HasFactory;

    protected $table = "ms_achievement_task";

    protected $fillable = [
        'achievement_id',
        'task_type_id',
        'payload',
    ];

    public $defaultSelects = [
        'payload',
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
        $model = new AchievementTask();
        return $model->defaultWith(is_null($selects) ? $model->defaultSelects : $selects, $query);
    }

    /**
     * function untuk setting default with apa saja yang akan sering dipakai
     * tetapi jangan banyak-banyak karena akan memperngaruhi proses loading page
     *
     * @param Relation|AchievementTask $query
     * @param array $selects
     *
     * @return Relation
     * */
    private function _defaultWith($query, $selects = [])
    {
        return $query->with([
            'task_type' => function($query) {
                Config::foreignWith($query, ['id', 'slug', DB::raw('config_name as text')]);
            }
        ])->select($this->getKeyName(), 'task_type_id')->addSelect($selects);
    }

    /**
     * function defaultWith yang digunakan untuk dipanggil public
     *
     * @param array $selects
     * @param Relation|AchievementTask|null $query
     *
     * @return Relation
     * */
    public function defaultWith($selects = [], $query = null)
    {
        return $this->_defaultWith(is_null($query) ? $this : $query, $selects);
    }

    public function task_type()
    {
        return $this->hasOne(Config::class, 'id', 'task_type_id');
    }
}
