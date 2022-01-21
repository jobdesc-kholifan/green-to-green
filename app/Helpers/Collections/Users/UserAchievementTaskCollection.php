<?php

namespace App\Helpers\Collections\Users;

use App\Helpers\Collections\Collection;

class UserAchievementTaskCollection extends Collection
{

    public function getId()
    {
        return $this->get('id');
    }

    public function getPoints()
    {
        return $this->get('points');
    }

    public function payload()
    {
        return $this->get('payload');
    }
}
