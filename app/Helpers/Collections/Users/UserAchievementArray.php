<?php

namespace App\Helpers\Collections\Users;

use Illuminate\Support\Collection;

class UserAchievementArray extends Collection
{

    public function __construct($items)
    {
        parent::__construct(collect($items)->map(function($data) {
            return new UserAchievementCollection($data);
        })->toArray());
    }

    /**
     * @return UserAchievementCollection[]
     * */
    public function all()
    {
        return parent::all();
    }

    /**
     * @param callable|null $callback
     * @param null $default
     * @return UserAchievementCollection
     * */
    public function first(callable $callback = null, $default = null)
    {
        $row = parent::first($callback, $default);
        return !is_null($row) ? $row : new UserAchievementCollection();
    }
}
