<?php

namespace App\Transformers;

use App\Task;
use App\User;
use League\Fractal\TransformerAbstract;

class TaskTransformer extends TransformerAbstract
{
    /**
     * Transform the resource into an array.
     *
     * @param Task $task
     * @return array
     *
     * @OA\Schema(
     *     schema="Task",
     *          @OA\Property(
     *              property="id",
     *              description="Task Id",
     *              type="integer",
     *          ),
     *          @OA\Property(
     *              property="userId",
     *              description="Owner User Id",
     *              type="integer",
     *          ),
     *          @OA\Property(
     *              property="title",
     *              description="Task Title",
     *              type="string",
     *          ),
     *          @OA\Property(
     *              property="description",
     *              description="Task Description",
     *              type="string",
     *          ),
     *          @OA\Property(
     *              property="createdAt",
     *              description="Task Created At",
     *              type="integer",
     *          ),
     *          @OA\Property(
     *              property="updatedAt",
     *              description="Task Upadted At",
     *              type="integer",
     *          ),
     * )
     */
    public function transform(Task $task)
    {
        return [
            'id' => $task->id,
            'userId' => $task->user_id,
            'title' => $task->title,
            'description' => $task->description,
            'createdAt' => $task->created_at->getTimestamp(),
            'updatedAt' => $task->updated_at->getTimestamp(),
        ];
    }
}
