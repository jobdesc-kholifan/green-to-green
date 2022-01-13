<?php

namespace App\Helpers\Collections\Achievements;

class TaskPayloadCollection
{

    protected $payload;

    public function __construct($payload = null)
    {
        $this->payload = json_decode($payload);
    }

    public function get($key, $default = null)
    {
        return !empty($this->payload->requirement->$key) ? $this->payload->requirement->$key : $default;
    }

    public function requirement()
    {
        return $this->payload->requirement;
    }
}
