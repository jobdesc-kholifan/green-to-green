<?php

namespace App\Helpers\Collections\Orders;

use App\Helpers\Collections\Collection;
use App\Helpers\Collections\Users\UserCollection;

class OrderScheduleCollection extends Collection
{

    public function getId()
    {
        return $this->get('id');
    }

    public function getDate($format = 'd/m/Y')
    {
        return dbDate($this->get('schedule_date'), $format);
    }

    public function getStaffId()
    {
        return $this->get('staff_id');
    }

    public function getDesc()
    {
        return $this->get('description');
    }

    /**
     * @return UserCollection
     * */
    public function getStaff()
    {
        if($this->hasNotEmpty('staff'))
            return new UserCollection($this->get('staff'));

        return new UserCollection();
    }
}
