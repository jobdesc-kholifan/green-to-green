<?php

namespace App\Helpers\Collections\Users;

use App\Helpers\Collections\Collection;
use App\Helpers\Collections\Configs\ConfigCollection;
use App\Models\Masters\User;
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
        return $this->get('pob');
    }

    public function getDateOfBirth()
    {
        return $this->get('dob');
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
}
