<?php

namespace App\Helpers\Collections\Achievements;

class TaskPayloadCollection
{

    protected $payload;

    protected $requirement = [];

    public function __construct($payload = null)
    {
        if(is_null($payload))
            $payload = json_encode(['requirement' => $this->requirement]);

        $this->payload = json_decode($payload);
    }

    public function get($key, $default = null)
    {
        return !empty($this->payload->requirement->$key) ? $this->payload->requirement->$key : $default;
    }

    public function set($key, $value)
    {
        $this->payload->requirement->$key = $value;
    }

    public function requirement()
    {
        return $this->payload->requirement;
    }
}
