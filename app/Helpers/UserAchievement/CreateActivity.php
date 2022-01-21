<?php

namespace App\Helpers\UserAchievement;

use App\Helpers\Collections\Achievements\AchievementTaskCollection;
use App\Helpers\Collections\Configs\ConfigCollection;
use App\Helpers\Collections\Users\UserAchievementCollection;
use App\Helpers\Collections\Users\UserAchievementTaskCollection;
use App\Helpers\Collections\Users\UserCollection;
use App\Models\Achievements\Achievement;
use App\Models\Achievements\AchievementTask;
use App\Models\Users\UserAchievement;
use App\Models\Users\UserAchievementTask;

class CreateActivity
{

    /* @var Achievement */
    protected $mAchievement;

    /* @var AchievementTask */
    protected $mAchievementTask;

    /* @var UserAchievement */
    protected $mUserAchievement;

    /* @var UserAchievementTask */
    protected $mUserAchievementTask;

    /* @var UserCollection */
    protected $user;

    /* @var ConfigCollection */
    protected $taskType;

    protected $taskPayload;

    /* @var UserAchievementCollection */
    protected $current;

    /* @var UserAchievementTaskCollection*/
    protected $currentTask;

    public function __construct($user)
    {
        $this->user = $user;

        $this->mAchievement = new Achievement();
        $this->mUserAchievement = new UserAchievement();
        $this->mUserAchievementTask = new UserAchievementTask();
    }

    /**
     * @param ConfigCollection $taskType
     * @return CreateActivity
     * */
    public function setTaskType($taskType)
    {
        $this->taskType = $taskType;

        return $this;
    }

    public function setTaskPayload($taskPayload)
    {
        $this->taskPayload = is_array($taskPayload) ? $taskPayload : [$taskPayload];
    }

    private function getCurrentAchievement()
    {
        return $this->mUserAchievement->defaultWith($this->mUserAchievement->defaultSelects)
            ->where('user_id', $this->user->getId())
            ->where('percentage', '<', 100)
            ->first();
    }

    private function getCurrentAchievementTask()
    {
        return $this->mUserAchievementTask->defaultWith($this->mUserAchievementTask->defaultSelects)
            ->where('user_achievement_id', $this->current->getId())
            ->where('task_type_id', $this->taskType->getId())
            ->first();
    }

    private function getAchievementComplete()
    {
        return $this->mUserAchievement->defaultWith($this->mUserAchievement->defaultSelects)
            ->where('user_id', $this->user->getId())
            ->where('percentage', 100)
            ->get();
    }

    private function getNextAchievement($completeAchievement)
    {
        return $this->mAchievement->defaultWith($this->mAchievement->defaultSelects)
            ->whereNotIn('id', $completeAchievement)
            ->first();
    }

    protected function current()
    {
        if(is_null($this->current)) {
            $current = $this->getCurrentAchievement();

            if(is_null($current)) {
                $achievementComplete = $this->getAchievementComplete();
                $nextAchievement = $this->getNextAchievement(
                    $achievementComplete->count() > 0 ? $achievementComplete->map(function($data) { return $data->achievement_id; })->toArray() : []
                );

                if(is_null($nextAchievement))
                    return true;

                $this->mUserAchievement->create([
                    'user_id' => $this->user->getId(),
                    'achievement_id' => $nextAchievement->id,
                    'percentage' => 0,
                    'points' => 0,
                ]);

                $current = $this->getCurrentAchievement();
            }

            $this->current = new UserAchievementCollection($current);
        }

        return $this;
    }

    public function currentTask()
    {
        $currentTask = $this->getCurrentAchievementTask();
        if(is_null($currentTask)) {
            $this->mUserAchievementTask->create([
                'user_achievement_id' => $this->current->getId(),
                'task_type_id' => $this->taskType->getId(),
                'points' => 0,
            ]);

            $currentTask = $this->getCurrentAchievementTask();
        }

        $this->currentTask = new UserAchievementTaskCollection($currentTask);
    }

    public function run()
    {
        $this->current();
        $this->currentTask();

        $taskPayload = false;
        foreach($this->current->getAchievement()->getTasks()->all() as $task) {
            if($task->getTaskType()->getSlug() == $this->taskType->getSlug()) {
                $taskPayload = $task->payload();
                break;
            }
        }

        if($taskPayload) {
            $countPoints = 0;
            foreach($this->taskPayload as $payload) {
                $points = $taskPayload->points($payload);
                if($this->currentTask->getPoints() < 100) {
                    $mUserAchievementTask = $this->mUserAchievementTask->find($this->currentTask->getId());
                    $mUserAchievementTask->update([
                        'points' => $this->currentTask->getPoints() + $points,
                        'payload' => $payload,
                    ]);

                    $countPoints += $points;
                }
            }
        }
    }

    public function updatePoints()
    {
        $tasks = $this->mUserAchievementTask->defaultWith($this->mUserAchievementTask->defaultSelects)
            ->where('user_achievement_id', $this->current->getId())
            ->get();

        $allPoints = 0;
        foreach($tasks as $task) {
            $allPoints += $task->points;
        }

        $userAchievement = $this->mUserAchievement->find($this->current->getId());
        $userAchievement->update([
            'points' => $allPoints,
            'percentage' => $allPoints/$tasks->count(),
        ]);
    }
}
