<?php

namespace App\Models\Users;

use App\Models\Achievements\Achievement;
use App\Models\Achievements\AchievementTask;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;

class UserAchievement extends Model
{
    use HasFactory;

    protected $table = "usr_achievement";

    protected $fillable = [
        "user_id",
        "achievement_id",
        "points",
        "percentage",
    ];

    public $defaultSelects = [
        "points",
        "percentage"
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
        $model = new UserAchievement();
        return $model->defaultWith(is_null($selects) ? $model->defaultSelects : $selects, $query);
    }

    /**
     * function untuk setting default with apa saja yang akan sering dipakai
     * tetapi jangan banyak-banyak karena akan memperngaruhi proses loading page
     *
     * @param Relation|UserAchievement $query
     * @param array $selects
     *
     * @return Relation
     * */
    private function _defaultWith($query, $selects = [])
    {
        return $query->with([
            'achievement' => function($query) {
                Achievement::foreignWith($query)
                    ->with([
                        'tasks' => function($query) {
                            AchievementTask::foreignWith($query)
                                ->addSelect('achievement_id');
                        }
                    ]);
            }
        ])->select($this->getKeyName(), 'achievement_id')->addSelect($selects);
    }

    /**
     * function defaultWith yang digunakan untuk dipanggil public
     *
     * @param array $selects
     * @param Relation|UserAchievement|null $query
     *
     * @return Relation
     * */
    public function defaultWith($selects = [], $query = null)
    {
        return $this->_defaultWith(is_null($query) ? $this : $query, $selects);
    }

    public function achievement()
    {
        return $this->hasOne(Achievement::class, 'id', 'achievement_id');
    }

    public function user_tasks()
    {
        return $this->hasMany(UserAchievementTask::class, 'user_achievement_id', 'id');
    }

    public function defaultQuery()
    {
        return $this->defaultWith($this->defaultSelects)
            ->with([
                'user_tasks' => function($query) {
                    UserAchievementTask::foreignWith($query)
                        ->addSelect('user_achievement_id');
                }
            ]);
    }
}
