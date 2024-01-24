<?php

namespace App\Interfaces;

interface TaskRepositoryInterface
{
    public function getTasks();
    public function createTask(array $taskDetails);
    public function updateTask($taskId, array $taskDetails);
    public function deleteTask($taskId);
    public function toggleTaskCompletion($taskId);
    public function getTaskReport();
}
