<?php

namespace App\Helpers\Collections\Configs;

use Illuminate\Support\Collection;

class ConfigArray extends Collection
{

    public function __construct($items)
    {
        parent::__construct(collect($items)->map(function($data) {
            return new ConfigCollection($data);
        })->toArray());
    }

    /**
     * @return ConfigCollection[]
     * */
    public function all()
    {
        return parent::all();
    }

    /**
     * @param callable|null $callback
     * @param null $default
     * @return ConfigCollection
     * */
    public function first(callable $callback = null, $default = null)
    {
        $row = parent::first($callback, $default);
        return !is_null($row) ? $row : new ConfigCollection();
    }
}
