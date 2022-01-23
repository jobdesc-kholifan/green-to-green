<?php

namespace App\Helpers\Collections\Users;

use App\Helpers\Collections\Collection;

class UserFollowCollection extends Collection
{

    public function getId()
    {
        return $this->get('id');
    }

    /**
     * @return UserCollection
     * */
    public function getUserFollow()
    {
        if($this->hasNotEmpty('user_follower'))
            return new UserCollection($this->get('user_follower'));

        return new UserCollection();
    }

    /**
     * @return UserCollection
     * */
    public function getUserFollowing()
    {
        if($this->hasNotEmpty('user_following'))
            return new UserCollection($this->get('user_following'));

        return new UserCollection();
    }
}
