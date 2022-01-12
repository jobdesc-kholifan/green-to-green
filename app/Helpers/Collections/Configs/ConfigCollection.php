<?php

namespace App\Helpers\Collections\Configs;

use App\Helpers\Collections\Collection;
use App\Models\Masters\Config;
use Illuminate\Database\Eloquent\Relations\Relation;

class ConfigCollection extends Collection
{

    /**
     * @param array $values
     * @param array|null $children
     * @return ConfigCollection
     * */
    static public function create($values, $children = null)
    {
        /* @var Config|Relation $config */
        $config = new Config();
        $create = new ConfigCollection($config->create($values));

        if(!is_null($children)) {
            foreach($children as $child) {
                $child['parent_id'] = $create->getId();
            }

            $config->insert($children);
        }

        return $create;
    }

    public function getId()
    {
        return $this->get('id');
    }

    public function getName()
    {
        return $this->get('name');
    }

    public function getSlug()
    {
        return $this->get('slug');
    }

    public function getSequence()
    {
        return $this->get('sequence');
    }

    public function getPayload()
    {
        return $this->get('payload');
    }

    /**
     * @return ConfigArray
     * */
    public function getChildren()
    {
        if($this->hasNotEmpty('children'))
            return new ConfigArray($this->get('children'));

        return new ConfigArray([]);
    }
}
