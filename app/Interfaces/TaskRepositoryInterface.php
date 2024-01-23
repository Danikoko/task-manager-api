<?php

namespace App\Interfaces;

interface TaskRepositoryInterface
{
    public function getTasks();
    public function createTask($taskDetails);
    public function updateTask($taskId, $taskDetails);
    public function deleteTask($taskId);
}
