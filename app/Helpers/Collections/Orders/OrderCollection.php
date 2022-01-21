<?php

namespace App\Helpers\Collections\Orders;

use App\Helpers\Collections\Collection;
use App\Helpers\Collections\Configs\ConfigCollection;
use App\Helpers\Collections\Users\UserCollection;

class OrderCollection extends Collection
{

    public function getId()
    {
        return $this->get('id');
    }

    public function getAddress()
    {
        return $this->get('address');
    }

    public function getLanLng()
    {
        return $this->get('lat_lng');
    }

    public function getDriverNote()
    {
        return $this->get('driver_note');
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

        return new UserCollection($this->get('user'));
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

    public function getScheduleId()
    {
        return $this->get('schedule_id');
    }

    /**
     * @return OrderScheduleCollection
     * */
    public function getSchedule()
    {
        if($this->hasNotEmpty('schedule'))
            return new OrderScheduleCollection($this->get('schedule'));

        return new OrderScheduleCollection();
    }
}
