<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Task;
use App\Services\TaskService;
use Illuminate\Http\JsonResponse;

class TaskController extends Controller
{
    public function __construct(protected TaskService $taskService)
    {
    }

    public function index(): JsonResponse
    {
        $tasks = $this->taskService->getAllTasks();
        return response()->json($tasks);
    }

    public function store(StoreTaskRequest $request): JsonResponse
    {
        $task = $this->taskService->createTask($request->validated());
        return response()->json($task, 201);
    }

    public function show(Task $task): JsonResponse
    {
        // O serviço de cache é chamado aqui para consistência, embora o findOrFail seja rápido.
        $cachedTask = $this->taskService->findTaskById($task->id);
        return response()->json($cachedTask);
    }

    public function update(UpdateTaskRequest $request, Task $task): JsonResponse
    {
        $updatedTask = $this->taskService->updateTask($task, $request->validated());
        return response()->json($updatedTask);
    }

    public function destroy(Task $task): JsonResponse
    {
        $this->taskService->deleteTask($task);
        return response()->json(null, 204);
    }

    public function toggle(Task $task): JsonResponse
    {
        $toggledTask = $this->taskService->toggleTaskStatus($task);
        return response()->json($toggledTask);
    }
}
