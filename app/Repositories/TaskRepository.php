<?php

namespace App\Repositories;

use App\Interfaces\TaskRepositoryInterface;
use App\Models\Task;
use App\Http\Resources\TaskResource;
use Illuminate\Http\JsonResponse;

class TaskRepository implements TaskRepositoryInterface
{
    public function getTasks(): JsonResponse
    {
        $tasks = Task::where('user_id', auth('sanctum')->id())->get();

        return response()->json([
            'status' => 'success',
            'data' => TaskResource::collection($tasks)
        ], 200);
    }

    public function createTask(array $taskDetails): JsonResponse
    {
        $taskDetails['user_id'] = auth('sanctum')->id();
        // Save the task
        $taskSaved = Task::create($taskDetails);
        if ($taskSaved) {
            $taskId = $taskSaved->id;
            $task = Task::findOrFail($taskId);

            return response()->json([
                'status' => 'success',
                'message' => 'Task added successfully.'
            ], 201);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'The task couldn\'t be added.'
            ], 401);
        }
    }

    public function updateTask($taskId, array $taskDetails): JsonResponse
    {
        $task = Task::findOrFail($taskId);
        // Check if this task belongs to the currently logged in user
        if ($task->user_id != auth('sanctum')->id()) {
            return response()->json([
                'status' => 'error',
                'message' => 'You do not own this task.'
            ], 403);
        }

        // Update the task
        $taskUpdated = Task::whereId($taskId)->update($taskDetails);
        if ($taskUpdated) {
            return response()->json([
                'status' => 'success',
                'message' => 'Task updated successfully.'
            ], 201);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'The task couldn\'t be updated.'
            ], 401);
        }
    }

    public function deleteTask($taskId): JsonResponse
    {
        $task = Task::find($taskId);

        // Check if this task belongs to the currently logged in user
        if ($task->user_id != auth('sanctum')->id()) {
            return response()->json([
                'status' => 'error',
                'message' => 'You do not own this task.'
            ], 403);
        }

        $taskDestroyed = Task::destroy($taskId);
        if ($taskDestroyed) {

            return response()->json([
                'status' => 'success',
                'message' => 'Task deleted successfully.'
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'The task couldn\'t be deleted.'
            ], 401);
        }
    }

    public function toggleTaskCompletion($taskId): JsonResponse
    {
        $task = Task::find($taskId);

        // Check if this task belongs to the currently logged in user
        if ($task->user_id != auth('sanctum')->id()) {
            return response()->json([
                'status' => 'error',
                'message' => 'You do not own this task.'
            ], 403);
        }

        $taskUpdated = Task::whereId($taskId)->update([
            'is_completed' => !$task->is_completed
        ]);

        if ($taskUpdated) {
            return response()->json([
                'status' => 'success',
                'message' => 'Task updated successfully.'
            ], 201);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'The task couldn\'t be updated.'
            ], 401);
        }
    }
}
