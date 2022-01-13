<?php

namespace App\Helpers\Collections\Configs;

use App\Helpers\Collections\CollectionFinder;
use App\Models\Masters\Config;

class ConfigFinderCollection extends CollectionFinder
{

    public function __construct($key, $keys, $items)
    {
        $this->model = new Config();

        parent::__construct($key, $keys, collect($items)->map(function($data) {
            return new ConfigCollection($data);
        }));
    }

    /**
     * @return ConfigCollection[]
     * */
    public function all()
    {
        return parent::all();
    }

    /**
     * @param string|null $keyValue
     * @param callable|null $callback
     * @return ConfigCollection
     *
     * @throws \Exception
     */
    public function get($keyValue = null, $callback = null)
    {
        return parent::get($keyValue, $callback);
    }

    /**
     * @throws \Exception
     * @var string|null $keyValue
     * @return ConfigCollection[]
     * */
    public function getArray($keyValue = null)
    {
        return parent::getArray($keyValue);
    }
}
