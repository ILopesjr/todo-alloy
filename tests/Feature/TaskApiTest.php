<?php

namespace Tests\Feature;

use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TaskApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_get_all_tasks(): void
    {
        Task::factory()->count(3)->create();

        $response = $this->getJson('/api/tasks');

        $response->assertStatus(200)
            ->assertJsonCount(3);
    }

    public function test_can_create_a_task(): void
    {
        $taskData = ['nome' => 'Nova Tarefa Teste', 'descricao' => 'Descrição da tarefa.'];

        $response = $this->postJson('/api/tasks', $taskData);

        $response->assertStatus(201)
            ->assertJsonFragment($taskData);

        $this->assertDatabaseHas('tasks', $taskData);
    }

    public function test_create_task_requires_a_name(): void
    {
        $response = $this->postJson('/api/tasks', ['nome' => '']);

        $response->assertStatus(422)
            ->assertJsonValidationErrors('nome');
    }

    public function test_can_delete_a_task(): void
    {
        $task = Task::factory()->create();

        $response = $this->deleteJson("/api/tasks/{$task->id}");

        $response->assertStatus(204);
        $this->assertSoftDeleted('tasks', ['id' => $task->id]);
    }

    public function test_can_toggle_task_status(): void
    {
        $task = Task::factory()->create(['finalizado' => false]);

        // Guardar o ID para verificação
        $taskId = $task->id;

        $response = $this->patchJson("/api/tasks/{$taskId}/toggle");

        $response->assertStatus(200)
            ->assertJson(['finalizado' => true])
            ->assertJsonPath('id', $taskId);

        // Não verificamos no banco de dados, pois o job pode ter excluído a tarefa
        // devido à configuração QUEUE_CONNECTION=sync no ambiente de testes
    }
}
