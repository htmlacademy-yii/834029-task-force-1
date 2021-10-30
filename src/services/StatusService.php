<?php

namespace taskforce\services;

use common\models\Response;
use common\models\Task;
use frontend\models\CompleteTaskForm;
use taskforce\models\exceptions\CantCompleteTaskException;

class StatusService
{
    public function acceptResponse(Response $response): bool
    {
        $transaction = Response::getDb()->beginTransaction();
        try {
            $response->status = Response::STATUS_ACCEPTED;
            $response->save();

            $task = Task::findOne($response->task_id);
            $task->status = \taskforce\models\Task::STATUS_IN_WORK;
            $task->worker_id = $response->worker_id;
            $task->save();
            $transaction->commit();
        } catch(\Throwable $e) {
            $transaction->rollBack();
            return false;
        }

        return true;
    }

    public function refuseResponse(Response $response): void
    {
        $response->status = Response::STATUS_REFUSED;
        $response->save();
    }

    public function completeTask(Task $task, CompleteTaskForm $form): bool
    {
        $transaction = Task::getDb()->beginTransaction();
        try {
            switch ($form->isComplete) {
                case $form::COMPLETE:
                    $task->status = \taskforce\models\Task::STATUS_COMPLETED;
                    break;
                case $form::DIFFICULT:
                    $task->status = \taskforce\models\Task::STATUS_FAILED;
                    break;
                default:
                    throw new CantCompleteTaskException();
            }
            $task->save();

            if (!$form->createReview($task)) {
                throw new CantCompleteTaskException();
            }

            $transaction->commit();
        } catch(\Throwable $e) {
            $transaction->rollBack();
            return false;
        }

        return true;
    }
}