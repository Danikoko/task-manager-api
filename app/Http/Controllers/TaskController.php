<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Interfaces\TaskRepositoryInterface;
use App\Http\Requests\TaskRequest;
use App\Models\Task;

class TaskController extends Controller
{
    private TaskRepositoryInterface $taskRepository;

    public function __construct(TaskRepositoryInterface $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }

    public function getTasks()
    {
        return $this->taskRepository->getTasks();
    }

    public function getSingleTask(Task $task)
    {
        return $this->taskRepository->getSingleTask($task->id);
    }

    public function createTask(TaskRequest $request)
    {
        $validatedData = $request->validated();
        return $this->taskRepository->createTask($validatedData);
    }

    public function updateTask(TaskRequest $request, Task $task)
    {
        $validatedData = $request->validated();
        return $this->taskRepository->updateTask($task->id, $validatedData);
    }

    public function deleteTask(Task $task)
    {
        return $this->taskRepository->deleteTask($task->id);
    }
}
