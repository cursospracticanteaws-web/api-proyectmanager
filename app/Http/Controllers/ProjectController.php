<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectRequest;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = $request->user()->projects();

        // Filtrar por estado archivado si se especifica
        if ($request->has('is_archived')) {
            $query->where('is_archived', $request->boolean('is_archived'));
        }

        $projects = $query->withCount('tasks')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return response()->json([
            'success' => true,
            'data' => [
                'projects' => ProjectResource::collection($projects->items()),
                'pagination' => [
                    'current_page' => $projects->currentPage(),
                    'per_page' => $projects->perPage(),
                    'total' => $projects->total(),
                    'last_page' => $projects->lastPage(),
                    'from' => $projects->firstItem(),
                    'to' => $projects->lastItem(),
                ]
            ]
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProjectRequest $request)
    {
        $project = $request->user()->projects()->create([
            'name' => $request->name,
            'description' => $request->description,
            'is_archived' => $request->boolean('is_archived', false),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Proyecto creado exitosamente',
            'data' => new ProjectResource($project->loadCount('tasks'))
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {
        $project = $request->user()->projects()->with('tasks')->find($id);

        if (!$project) {
            return response()->json([
                'success' => false,
                'message' => 'Proyecto no encontrado'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => new ProjectResource($project->loadCount('tasks'))
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProjectRequest $request, string $id)
    {
        $project = $request->user()->projects()->find($id);

        if (!$project) {
            return response()->json([
                'success' => false,
                'message' => 'Proyecto no encontrado'
            ], 404);
        }

        $project->update([
            'name' => $request->name,
            'description' => $request->description,
            'is_archived' => $request->boolean('is_archived', $project->is_archived),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Proyecto actualizado exitosamente',
            'data' => new ProjectResource($project->loadCount('tasks'))
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        $project = $request->user()->projects()->find($id);

        if (!$project) {
            return response()->json([
                'success' => false,
                'message' => 'Proyecto no encontrado'
            ], 404);
        }

        $project->delete();

        return response()->json([
            'success' => true,
            'message' => 'Proyecto eliminado exitosamente'
        ], 200);
    }

    /**
     * Archive or unarchive a project
     */
    public function archive(Request $request, string $id)
    {
        $project = $request->user()->projects()->find($id);

        if (!$project) {
            return response()->json([
                'success' => false,
                'message' => 'Proyecto no encontrado'
            ], 404);
        }

        $project->update([
            'is_archived' => !$project->is_archived
        ]);

        $message = $project->is_archived 
            ? 'Proyecto archivado exitosamente' 
            : 'Proyecto desarchivado exitosamente';

        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => new ProjectResource($project->loadCount('tasks'))
        ], 200);
    }
}
