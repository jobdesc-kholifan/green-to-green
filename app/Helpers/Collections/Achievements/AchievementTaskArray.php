<?php

namespace App\Helpers\Collections\Achievements;

use Illuminate\Support\Collection;

class AchievementTaskArray extends Collection
{

    public function __construct($items)
    {
        parent::__construct(collect($items)->map(function($data) {
            return new AchievementTaskCollection($data);
        })->toArray());
    }

    /**
     * @return AchievementTaskCollection[]
     * */
    public function all()
    {
        return parent::all();
    }

    /**
     * @param string|mixed $key
     * @param mixed|null $default
     * @return AchievementTaskCollection
     * */
    public function get($key, $default = null)
    {
        return parent::get($key, $default);
    }

    /**
     * @param callable|null $callback
     * @param null $default
     * @return AchievementTaskCollection
     * */
    public function first(callable $callback = null, $default = null)
    {
        $first = parent::first($callback, $default);
        return !is_null($first) ? $first : new AchievementTaskCollection();
    }
}
