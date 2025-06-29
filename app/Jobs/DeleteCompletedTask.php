<?php

namespace App\Jobs;

use App\Models\Task;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class DeleteCompletedTask implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(protected Task $task)
    {
        $this->onQueue('default');
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if ($this->task->exists && $this->task->finalizado) {
            Log::info("Executando job de exclusão definitiva para a tarefa ID: {$this->task->id}");
            $this->task->forceDelete(); // Exclusão definitiva
        } else {
            Log::warning("Job de exclusão cancelado para a tarefa ID: {$this->task->id}. Tarefa não existe mais ou não está finalizada.");
        }
    }
}
