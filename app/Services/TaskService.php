<?php

namespace App\Services;

use App\Jobs\DeleteCompletedTask;
use App\Models\Task;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

class TaskService
{
    protected const CACHE_TAG = 'tasks';
    protected const CACHE_TTL = 3600; // 1 hora

    /**
     * Obtém todas as tarefas não finalizadas, usando cache.
     */
    public function getAllTasks(): Collection
    {
        return Cache::tags(self::CACHE_TAG)->remember('all_tasks', self::CACHE_TTL, function () {
            return Task::orderBy('created_at', 'desc')->get();
        });
    }

    /**
     * Cria uma nova tarefa e inválida o cache.
     */
    public function createTask(array $data): Task
    {
        $task = Task::create($data);
        $this->invalidateCache();
        return $task;
    }

    /**
     * Encontra uma tarefa por ID, usando cache.
     */
    public function findTaskById(int $id): ?Task
    {
        $cacheKey = "task_{$id}";
        return Cache::tags(self::CACHE_TAG)->remember($cacheKey, self::CACHE_TTL, function () use ($id) {
            return Task::findOrFail($id);
        });
    }

    /**
     * Atualiza uma tarefa e inválida o cache.
     */
    public function updateTask(Task $task, array $data): Task
    {
        $task->update($data);
        $this->invalidateCache();
        return $task->fresh();
    }

    /**
     * Alterna o status de finalização e despacha o job de exclusão se necessário.
     */
    public function toggleTaskStatus(Task $task): Task
    {
        $task->finalizado = !$task->finalizado;
        $task->save();

        if ($task->finalizado) {
            // Despacha o job para ser executado em 10 minutos
            DeleteCompletedTask::dispatch($task)->delay(now()->addMinutes(10));
        }

        $this->invalidateCache();
        return $task;
    }

    /**
     * Exclui (soft delete) uma tarefa e inválida o cache.
     */
    public function deleteTask(Task $task): void
    {
        $task->delete();
        $this->invalidateCache();
    }

    /**
     * Inválida o cache de tarefas.
     */
    public function invalidateCache(): void
    {
        Cache::tags(self::CACHE_TAG)->flush();
    }
}
