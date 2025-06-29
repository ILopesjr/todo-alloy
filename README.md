# Teste Técnico Alloy - To-Do List

## Descrição do Projeto

Este é um teste técnico para desenvolvedores da Alloy, consistindo na implementação de uma aplicação de lista de tarefas (To-Do List) utilizando **Laravel 12** como backend e **Vue.js 3** como frontend.

## Objetivo do Teste

O candidato deve implementar uma aplicação completa de gerenciamento de tarefas que demonstre conhecimentos em:

- Desenvolvimento de APIs RESTful com Laravel
- Frontend com Vue.js incluindo gerenciamento de estado
- Integração entre backend e frontend
- Banco de dados e migrations
- Testes automatizados

## Requisitos para Execução

- Docker e Docker Compose
- Git

## Como Executar com Laravel Sail

O Laravel Sail é uma interface de linha de comando leve para interagir com o ambiente Docker do Laravel. Siga os passos abaixo para executar o projeto:

### 1. Clone o repositório

```bash
git clone https://github.com/seu-usuario/testealloylaravel.git
cd testealloylaravel
```

### 2. Configure o ambiente

Copie o arquivo de exemplo de variáveis de ambiente:

```bash
cp .env.example .env
```

### 3. Inicialize o Laravel Sail

Se você está executando o Sail pela primeira vez, execute:

```bash
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php82-composer:latest \
    composer install --ignore-platform-reqs
```

### 4. Inicie os contêineres Docker

```bash
./vendor/bin/sail up -d
```

### 5. Configure a aplicação

Execute as migrations para criar as tabelas no banco de dados:

```bash
./vendor/bin/sail artisan migrate
```

Opcionalmente, você pode popular o banco de dados com dados de exemplo:

```bash
./vendor/bin/sail artisan db:seed
```

### 6. Instale as dependências JavaScript e compile os assets

```bash
./vendor/bin/sail npm install
./vendor/bin/sail npm run dev
```

### 7. Acesse a aplicação

A aplicação estará disponível em:

- Frontend: [http://localhost](http://localhost)
- API: [http://localhost/api/tasks](http://localhost/api/tasks)

## Comandos Úteis do Laravel Sail

- Iniciar os contêineres: `./vendor/bin/sail up -d`
- Parar os contêineres: `./vendor/bin/sail down`
- Executar comandos do Artisan: `./vendor/bin/sail artisan [comando]`
- Executar comandos do NPM: `./vendor/bin/sail npm [comando]`
- Executar comandos do Composer: `./vendor/bin/sail composer [comando]`
- Executar testes: `./vendor/bin/sail test`
- Acessar o terminal do contêiner: `./vendor/bin/sail shell`
- Verificar logs: `./vendor/bin/sail logs`

## Banco de Dados

Por padrão, o Laravel Sail configura um banco de dados MySQL. As credenciais estão definidas no arquivo `.env`. Você pode acessar o banco de dados via:

```
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=sail
DB_PASSWORD=password
```

## Testes

Execute os testes automatizados com:

```bash
./vendor/bin/sail test
```

## Funcionalidades da Aplicação

- Visualização de todas as tarefas
- Criação de novas tarefas
- Edição de tarefas existentes
- Exclusão de tarefas
- Marcação de tarefas como concluídas
- As tarefas concluídas são automaticamente removidas após 10 minutos

## Tecnologias Utilizadas

- **Backend:** Laravel 12, PHP 8.2
- **Frontend:** Vue.js 3, Tailwind CSS
- **Banco de Dados:** MySQL
- **Outros:** Docker, Laravel Sail, Pinia, Axios

## Estrutura do Projeto

```
├── app/
│   ├── Http/Controllers/     # Controllers da API
│   ├── Models/              # Models Eloquent
│   ├── Jobs/                # Jobs para processamento em fila
│   └── Services/            # Services para lógica de negócio
├── database/
│   ├── migrations/          # Migrações do banco
│   └── seeders/            # Seeders para dados iniciais
├── resources/
│   ├── js/
│   │   ├── components/      # Componentes Vue.js
│   │   ├── stores/         # Stores Pinia
│   │   └── services/       # Services para API
│   ├── css/                # Estilos CSS
│   └── views/              # Views Blade
├── routes/
│   ├── web.php             # Rotas web
│   └── api.php             # Rotas da API
└── public/webflow/         # Referência de design
```

## Funcionalidades Requeridas

### 1. Gerenciamento de Tarefas (CRUD)

#### Campos da Tarefa:
- `id` - Identificador único
- `nome` - Nome da tarefa (string, obrigatório)
- `descricao` - Descrição detalhada (text, opcional)
- `finalizado` - Status de conclusão (boolean, padrão: false)
- `data_limite` - Data limite para conclusão (datetime, opcional)
- `created_at` - Data de criação
- `updated_at` - Data da última atualização
- `deleted_at` - Data de exclusão (soft delete)

#### Operações:
- **Criar** nova tarefa
- **Listar** todas as tarefas (não excluídas)
- **Visualizar** tarefa específica
- **Editar** tarefa existente (clique para editar)
- **Marcar** como finalizada/não finalizada
- **Excluir** tarefa (soft delete)

### 2. Interface do Usuário

- Interface baseada no design disponível em `public/webflow/index.html`
- Lista de tarefas responsiva
- Modal para criação/edição de tarefas
- Botões de ação (editar, finalizar, excluir)
- Feedback visual para diferentes estados das tarefas

### 3. Sistema de Filas e Jobs

- **Job de Exclusão Automática**: Após uma tarefa ser marcada como finalizada, deve ser criado um job que será executado em 10 minutos para excluir definitivamente o registro
- Configuração de fila para processamento assíncrono

### 4. Sistema de Cache

- **Cache para Requests GET**: Implementar cache para listagem e visualização de tarefas
- **Invalidação de Cache**: Gerenciar invalidação automática quando dados são modificados (CREATE, UPDATE, DELETE)
- Tags de cache para invalidação granular

## Requisitos de Implementação

### Backend (Laravel)

1. **Model**
   ```php
   // Exemplo da estrutura esperada
   class Task extends Model
   {
       use SoftDeletes;
       
       protected $fillable = [
           'nome', 'descricao', 'finalizado', 'data_limite'
       ];
       
       protected $casts = [
           'finalizado' => 'boolean',
           'data_limite' => 'datetime',
       ];
   }
   ```

2. **Controller**
   - `TaskController` com métodos RESTful
   - Validação de dados de entrada
   - Respostas JSON padronizadas

3. **Routes**
   ```php
   // API Routes
   Route::apiResource('tasks', TaskController::class);
   Route::patch('tasks/{task}/toggle', [TaskController::class, 'toggle']);
   ```

4. **Migration**
   - Criação da tabela `tasks` com todos os campos necessários
   - Índices apropriados para performance

5. **Job**
   ```php
   class DeleteCompletedTask implements ShouldQueue
   {
       // Implementar lógica de exclusão definitiva
   }
   ```

6. **Cache**
   - Implementar cache com tags
   - Service ou Repository pattern para gerenciar cache

### Frontend (Vue.js)

1. **Componentes**
   - `TaskList.vue` - Lista de tarefas
   - `TaskItem.vue` - Item individual de tarefa
   - `TaskModal.vue` - Modal para criar/editar
   - `TaskForm.vue` - Formulário de tarefa

2. **Store (Pinia)**
   ```javascript
   // Exemplo de estrutura
   export const useTaskStore = defineStore('tasks', {
     state: () => ({
       tasks: [],
       loading: false,
     }),
     actions: {
       async fetchTasks() { /* ... */ },
       async createTask(task) { /* ... */ },
       async updateTask(id, task) { /* ... */ },
       async deleteTask(id) { /* ... */ },
       async toggleTask(id) { /* ... */ },
     }
   })
   ```

3. **Services**
   - `taskService.js` - Comunicação com API
   - Interceptors para tratamento de erros
   - Headers de autenticação se necessário

## Configuração e Execução

### Pré-requisitos
- PHP 8.2+
- Composer
- Node.js 18+
- SQLite

### Instalação

1. **Clone e instale dependências:**
   ```bash
   composer install
   npm install
   ```

2. **Configuração do ambiente:**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

3. **Configuração do banco de dados (.env):**
   ```env
   DB_CONNECTION=sqlite
   DB_DATABASE=database/database.sqlite
   ```

4. **Execute as migrações:**
   ```bash
   php artisan migrate
   ```

5. **Execute o projeto:**
   ```bash
   composer run dev
   ```
   
   Ou alternativamente:
   ```bash
   # Terminal 1 - Laravel
   php artisan serve
   
   # Terminal 2 - Queue Worker
   php artisan queue:work
   
   # Terminal 3 - Vite
   npm run dev
   ```

### Scripts Disponíveis

- `composer run dev` - Executa todos os serviços simultaneamente
- `composer run test` - Executa os testes
- `npm run dev` - Desenvolvimento frontend
- `npm run build` - Build de produção

## Critérios de Avaliação

### Obrigatórios
- [ ] CRUD completo de tarefas funcionando
- [ ] Interface baseada no design fornecido
- [ ] Sistema de filas implementado
- [ ] Cache implementado com invalidação
- [ ] Soft deletes funcionando
- [ ] Código limpo e bem estruturado

### Diferenciais
- [ ] Testes unitários/feature
- [ ] Tratamento de erros robusto
- [ ] Validações frontend e backend
- [ ] Responsividade da interface
- [ ] Documentação de código
- [ ] Otimizações de performance

## Estrutura de Entrega

### Arquivos Principais a Implementar

1. **Backend:**
   - `app/Models/Task.php`
   - `app/Http/Controllers/TaskController.php`
   - `app/Jobs/DeleteCompletedTask.php`
   - `database/migrations/xxxx_create_tasks_table.php`
   - `routes/api.php` (adição das rotas)

2. **Frontend:**
   - `resources/js/stores/taskStore.js`
   - `resources/js/services/taskService.js`
   - `resources/js/components/TaskList.vue`
   - `resources/js/components/TaskModal.vue`
   - Atualização do `TasksContainer.vue`

### Documentação
- README.md atualizado com instruções específicas
- Comentários no código explicando lógicas complexas
- Documentação da API (opcional, mas valorizado)

## Dicas de Implementação

1. **Use o design fornecido** em `public/webflow/index.html` como referência visual
2. **Implemente primeiro o CRUD básico**, depois adicione cache e filas
3. **Valide dados** tanto no frontend quanto no backend
4. **Use transações** para operações que envolvem múltiplas tabelas
5. **Implemente loading states** para melhor UX
6. **Trate erros** de forma amigável ao usuário

## Contato

Para dúvidas sobre o teste, entre em contato com a equipe de desenvolvimento da Alloy.

---

**Boa sorte! 🚀**
