<?php

namespace App\Helpers\Collections\Achievements;

interface TaskPayloadContract
{

    public function payload();

    public function createPayload();

    public function points($payload);

    public function messages($payload);
}
