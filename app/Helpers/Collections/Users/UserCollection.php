<?php

namespace App\Helpers\Collections\Users;

use App\Helpers\Collections\Collection;
use App\Helpers\Collections\Configs\ConfigCollection;
use App\Models\Masters\User;
use App\Models\Masters\UserFollow;
use Illuminate\Database\Eloquent\Relations\Relation;

class UserCollection extends Collection
{

    /**
     * @param array $values
     * @return UserCollection
     * */
    static public function create($values)
    {
        /* @var User|Relation $user */
        $user = new User();
        return new UserCollection($user->create($values));
    }

    /**
     * @return UserCollection
     * */
    static public function current()
    {
        /* @var User|Relation $user */
        $user = new User();
        return new UserCollection(
            $user->defaultQuery()->find(auth()->id())
        );
    }

    public function getId()
    {
        return $this->get('id');
    }

    public function getFullName()
    {
        return $this->get('full_name');
    }

    public function getGenderId()
    {
        return $this->get('gender_id');
    }

    /**
     * @return ConfigCollection
     * */
    public function getGender()
    {
        if($this->hasNotEmpty('gender'))
            return new ConfigCollection($this->get('gender'));

        return new ConfigCollection();
    }

    public function getPlaceOfBirth()
    {
        return $this->get('place_of_birth');
    }

    public function getDateOfBirth($format = 'd/m/Y')
    {
        $date = $this->get('date_of_birth');
        return !empty($date) ? dbDate($date, $format) : null;
    }

    public function getAddress()
    {
        return $this->get('address');
    }

    public function getEmail()
    {
        return $this->get('email');
    }

    public function getPhoneNumber()
    {
        return $this->get('phone_number');
    }

    public function getRoleId()
    {
        return $this->get('role_id');
    }

    /**
     * @return ConfigCollection
     * */
    public function getRole()
    {
        if($this->hasNotEmpty('role'))
            return new ConfigCollection($this->get('role'));

        return new ConfigCollection();
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

    public function getUserName()
    {
        return $this->get('user_name');
    }

    public function getPassword()
    {
        return $this->get('user_password');
    }

    /**
     * @return UserFollowArray
     * */
    public function getFollowers()
    {
        if($this->hasNotEmpty('followers'))
            return new UserFollowArray($this->get('followers'));

        return new UserFollowArray([]);
    }

    /**
     * @return UserFollowArray
     * */
    public function getFollowing()
    {
        if($this->hasNotEmpty('followings'))
            return new UserFollowArray($this->get('followings'));

        return new UserFollowArray([]);
    }

    public function getUrlProfile()
    {
        return $this->get('preview');
    }
}
