<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use App\Models\Project;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Obtener todas las tareas de los proyectos del usuario
        $query = Task::whereHas('project', function ($q) use ($request) {
            $q->where('user_id', $request->user()->id);
        });

        // Filtrar por proyecto si se especifica
        if ($request->has('project_id')) {
            $query->where('project_id', $request->project_id);
        }

        // Filtrar por estado completado si se especifica
        if ($request->has('is_completed')) {
            $query->where('is_completed', $request->boolean('is_completed'));
        }

        // Filtrar por fecha de vencimiento
        if ($request->has('due_date')) {
            $query->whereDate('due_date', $request->due_date);
        }

        $tasks = $query->with('project')
            ->orderBy('due_date', 'asc')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return response()->json([
            'success' => true,
            'data' => [
                'tasks' => TaskResource::collection($tasks->items()),
                'pagination' => [
                    'current_page' => $tasks->currentPage(),
                    'per_page' => $tasks->perPage(),
                    'total' => $tasks->total(),
                    'last_page' => $tasks->lastPage(),
                    'from' => $tasks->firstItem(),
                    'to' => $tasks->lastItem(),
                ]
            ]
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TaskRequest $request)
    {
        // Verificar que el proyecto pertenece al usuario
        $project = Project::where('id', $request->project_id)
            ->where('user_id', $request->user()->id)
            ->first();

        if (!$project) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes permiso para agregar tareas a este proyecto'
            ], 403);
        }

        $task = Task::create([
            'project_id' => $request->project_id,
            'title' => $request->title,
            'description' => $request->description,
            'due_date' => $request->due_date,
            'is_completed' => $request->boolean('is_completed', false),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Tarea creada exitosamente',
            'data' => new TaskResource($task->load('project'))
        ], 201);
    }

    /**
     * Display the specified resource. 
     */
    public function show(Request $request, string $id)
    {
        $task = Task::whereHas('project', function ($q) use ($request) {
            $q->where('user_id', $request->user()->id);
        })->with('project')->find($id);

        if (!$task) {
            return response()->json([
                'success' => false,
                'message' => 'Tarea no encontrada'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => new TaskResource($task)
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TaskRequest $request, string $id)
    {
        $task = Task::whereHas('project', function ($q) use ($request) {
            $q->where('user_id', $request->user()->id);
        })->find($id);

        if (!$task) {
            return response()->json([
                'success' => false,
                'message' => 'Tarea no encontrada'
            ], 404);
        }

        // Verificar que el nuevo proyecto pertenece al usuario
        $project = Project::where('id', $request->project_id)
            ->where('user_id', $request->user()->id)
            ->first();

        if (!$project) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes permiso para mover la tarea a este proyecto'
            ], 403);
        }

        $task->update([
            'project_id' => $request->project_id,
            'title' => $request->title,
            'description' => $request->description,
            'due_date' => $request->due_date,
            'is_completed' => $request->boolean('is_completed', $task->is_completed),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Tarea actualizada exitosamente',
            'data' => new TaskResource($task->load('project'))
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        $task = Task::whereHas('project', function ($q) use ($request) {
            $q->where('user_id', $request->user()->id);
        })->find($id);

        if (!$task) {
            return response()->json([
                'success' => false,
                'message' => 'Tarea no encontrada'
            ], 404);
        }

        $task->delete();

        return response()->json([
            'success' => true,
            'message' => 'Tarea eliminada exitosamente'
        ], 200);
    }

    /**
     * Mark a task as completed or incomplete
     */
    public function complete(Request $request, string $id)
    {
        $task = Task::whereHas('project', function ($q) use ($request) {
            $q->where('user_id', $request->user()->id);
        })->find($id);

        if (!$task) {
            return response()->json([
                'success' => false,
                'message' => 'Tarea no encontrada'
            ], 404);
        }

        $task->update([
            'is_completed' => !$task->is_completed
        ]);

        $message = $task->is_completed 
            ? 'Tarea marcada como completada' 
            : 'Tarea marcada como pendiente';

        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => new TaskResource($task->load('project'))
        ], 200);
    }
}
