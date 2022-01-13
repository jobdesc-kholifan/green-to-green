<?php

namespace App\Models\Masters;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory;

    protected $table = "ms_users";

    protected $fillable = [
        'full_name',
        'gender_id',
        'pob',
        'dob',
        'email',
        'phone_number',
        'user_name',
        'user_password',
        'role_id',
        'status_id'
    ];

    public $defaultSelects = [
        'full_name',
        'pob',
        'dob',
        'email',
        'phone_number',
        'user_name'
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
        $model = new User();
        return $model->defaultWith(is_null($selects) ? $model->defaultSelects : $selects, $query);
    }

    /**
     * function untuk setting default with apa saja yang akan sering dipakai
     * tetapi jangan banyak-banyak karena akan memperngaruhi proses loading page
     *
     * @param Relation|User $query
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
     * @param Relation|User|null $query
     *
     * @return Relation
     * */
    public function defaultWith($selects = [], $query = null)
    {
        return $this->_defaultWith(is_null($query) ? $this : $query, $selects);
    }

    public function getAuthPassword()
    {
        return $this->user_password;
    }

    public function role()
    {
        return $this->hasOne(Config::class, 'id', 'role_id');
    }

    public function status()
    {
        return $this->hasOne(Config::class, 'id', 'status_id');
    }
}
