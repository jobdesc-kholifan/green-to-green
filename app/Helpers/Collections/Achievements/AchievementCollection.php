<?php

namespace App\Helpers\Collections\Achievements;

use App\Helpers\Collections\Collection;
use App\Helpers\Collections\Configs\ConfigCollection;
use App\Helpers\Collections\Users\UserAchievementArray;
use App\Helpers\Collections\Users\UserAchievementCollection;
use App\Models\Achievements\Achievement;
use Illuminate\Database\Eloquent\Relations\Relation;

class AchievementCollection extends Collection
{

    /**
     * @param array $values
     * @return AchievementCollection
     * */
    static public function create($values)
    {
        /* @var Achievement|Relation $achievement */
        $achievement = new Achievement();
        return new AchievementCollection($achievement->create($values));
    }

    public function getId()
    {
        return $this->get('id');
    }

    public function getTitle()
    {
        return $this->get('title');
    }

    public function getDesc()
    {
        return $this->get('description');
    }

    public function getStatusId()
    {
        return $this->get('status_id');
    }

    /**
     * @return ConfigCollection
     * */
    public function getStatus()
    {
        if($this->hasNotEmpty('status'))
            return new ConfigCollection($this->get('status'));

        return new ConfigCollection();
    }

    /**
     * @return AchievementTaskArray
     * */
    public function getTasks()
    {
        if($this->hasNotEmpty('tasks'))
            return new AchievementTaskArray($this->get('tasks'));

        return new AchievementTaskArray([]);
    }

    public function getUserAchievement()
    {
        if($this->hasNotEmpty('user_achievement'))
            return new UserAchievementCollection($this->get('user_achievement'));

        return new UserAchievementCollection();
    }
}
