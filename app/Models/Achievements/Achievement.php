<?php

namespace App\Models\Achievements;

use App\Models\Masters\Config;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;

class Achievement extends Model
{
    use HasFactory;

    protected $table = "ms_achievement";

    protected $fillable = [
        'title',
        'description',
        'status_id',
    ];

    public $defaultSelects = [
        'title',
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
        $model = new Achievement();
        return $model->defaultWith(is_null($selects) ? $model->defaultSelects : $selects, $query);
    }

    /**
     * function untuk setting default with apa saja yang akan sering dipakai
     * tetapi jangan banyak-banyak karena akan memperngaruhi proses loading page
     *
     * @param Relation|Achievement $query
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
     * @param Relation|Achievement|null $query
     *
     * @return Relation
     * */
    public function defaultWith($selects = [], $query = null)
    {
        return $this->_defaultWith(is_null($query) ? $this : $query, $selects);
    }

    public function status()
    {
        return $this->hasOne(Config::class, 'id', 'status_id');
    }

    public function tasks()
    {
        return $this->hasMany(AchievementTask::class, 'achievement_id', 'id');
    }

    public function defaultQuery()
    {
        return $this->defaultWith($this->defaultSelects)
            ->with([
                'status' => function($query) {
                    Config::foreignWith($query);
                }
            ])
            ->addSelect('status_id');
    }
}
