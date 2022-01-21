<?php

namespace App\Helpers\Collections\Users;

use App\Helpers\Collections\Achievements\AchievementCollection;
use App\Helpers\Collections\Collection;

class UserAchievementCollection extends Collection
{

    public function getId()
    {
        return $this->get('id');
    }

    public function getUserId()
    {
        return $this->get('user_id');
    }

    /**
     * @return UserCollection
     * */
    public function getUser()
    {
        if($this->hasNotEmpty('user'))
            return new UserCollection($this->get('user'));

        return new UserCollection();
    }

    public function getAchievementId()
    {
        return $this->get('achievement_id');
    }

    /**
     * @return AchievementCollection
     * */
    public function getAchievement()
    {
        if($this->hasNotEmpty('achievement'))
            return new AchievementCollection($this->get('achievement'));

        return new AchievementCollection();
    }

    public function getPoints()
    {
        return $this->get('points');
    }

    public function getPercentage()
    {
        return $this->get('percentage');
    }

    public function payload()
    {
        return $this->get('payload');
    }
}
