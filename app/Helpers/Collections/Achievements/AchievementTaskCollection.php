<?php

namespace App\Helpers\Collections\Achievements;

use App\Helpers\Collections\Collection;
use App\Helpers\Collections\Configs\ConfigCollection;
use App\Models\Achievements\AchievementTask;
use Illuminate\Database\Eloquent\Relations\Relation;

class AchievementTaskCollection extends Collection
{

    /**
     * @param array $values
     * @return AchievementTaskCollection
     * */
    static public function create($values)
    {
        /* @var AchievementTask|Relation $achievementTask */
        $achievementTask = new AchievementTask();
        return new AchievementTaskCollection($achievementTask->create($values));
    }

    public function getId()
    {
        return $this->get('id');
    }

    public function getAchievementId()
    {
        return $this->get('achievement_id');
    }

    public function getTaskTypeId()
    {
        return $this->get('task_type_id');
    }

    /**
     * @return ConfigCollection
     * */
    public function getTaskType()
    {
        if($this->hasNotEmpty('task_type'))
            return new ConfigCollection($this->get('task_type'));

        return new ConfigCollection();
    }

    /**
     * @return TasksCreateRequestPayload|TasksCollectPlasticPayload|TasksRegisterPayload|TaskPayloadCollection
     * */
    public function payload()
    {
        $payload = $this->get('payload');

        if($this->getTaskType()->getSlug() == \DBTypes::tasksCollectPlastic)
            return new TasksCollectPlasticPayload($payload);

        else if($this->getTaskType()->getSlug() === \DBTypes::tasksCreatePickup)
            return new TasksCreateRequestPayload($payload);

        else if($this->getTaskType()->getSlug() === \DBTypes::tasksRegister)
            return new TasksRegisterPayload($payload);

        return new TaskPayloadCollection($payload);
    }

    /**
     * @return TasksCreateRequestPayload|TasksCollectPlasticPayload|TasksRegisterPayload|TaskPayloadCollection
     * */
    public function user_payload()
    {
        return $this->get('user_payload');
    }
}
