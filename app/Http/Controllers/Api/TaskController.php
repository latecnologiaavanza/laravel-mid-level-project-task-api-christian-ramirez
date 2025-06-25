<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/tasks",
     *     summary="Listar todas las tareas",
     *     tags={"Tareas"},
     *     @OA\Parameter(name="status", in="query", @OA\Schema(type="string")),
     *     @OA\Parameter(name="priority", in="query", @OA\Schema(type="string")),
     *     @OA\Parameter(name="due_date", in="query", @OA\Schema(type="string", format="date")),
     *     @OA\Parameter(name="project_id", in="query", @OA\Schema(type="string")),
     *     @OA\Response(response=200, description="Lista de tareas")
     * )
     */
    public function index(Request $request)
    {
        $query = Task::query();

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        if ($request->has('priority')) {
            $query->where('priority', $request->priority);
        }
        if ($request->has('due_date')) {
            $query->where('due_date', $request->due_date);
        }
        if ($request->has('project_id')) {
            $query->where('project_id', $request->project_id);
        }

        return response()->json($query->orderBy('due_date', 'asc')->paginate(10));
    }

    /**
     * @OA\Post(
     *     path="/api/tasks",
     *     summary="Crear nueva tarea",
     *     tags={"Tareas"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"project_id","title","status","priority","due_date"},
     *             @OA\Property(property="project_id", type="string", example="uuid"),
     *             @OA\Property(property="title", type="string", example="Crear login"),
     *             @OA\Property(property="description", type="string", example="Usar JWT"),
     *             @OA\Property(property="status", type="string", enum={"pending", "in_progress", "done"}, example="pending"),
     *             @OA\Property(property="priority", type="string", enum={"low", "medium", "high"}, example="high"),
     *             @OA\Property(property="due_date", type="string", format="date", example="2025-01-01")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Tarea creada")
     * )
     */
    public function store(Request $request)
    {
        $task = Task::create($request->validate());
        return response()->json($task, 201);
    }

    /**
     * @OA\Get(
     *     path="/api/tasks/{id}",
     *     summary="Ver una tarea",
     *     tags={"Tareas"},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="string")),
     *     @OA\Response(response=200, description="Detalle de la tarea")
     * )
     */
    public function show(string $id)
    {
        $task = Task::with('project')->findOrFail($id);
        return response()->json($task);
    }

    /**
     * @OA\Put(
     *     path="/api/tasks/{id}",
     *     summary="Actualizar tarea",
     *     tags={"Tareas"},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="string")),
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             @OA\Property(property="title", type="string", example="Login actualizado"),
     *             @OA\Property(property="description", type="string", example="Login JWT y validación"),
     *             @OA\Property(property="status", type="string", enum={"pending", "in_progress", "done"}, example="done"),
     *             @OA\Property(property="priority", type="string", enum={"low", "medium", "high"}, example="medium"),
     *             @OA\Property(property="due_date", type="string", format="date", example="2025-02-01"),
     *             @OA\Property(property="project_id", type="string", example="uuid-del-proyecto")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Tarea actualizada")
     * )
     */
    public function update(Request $request, string $id)
    {
        $task = Task::findOrFail($id);
        $task->update($request->validate());
        return response()->json($task);
    }

    /**
     * @OA\Delete(
     *     path="/api/tasks/{id}",
     *     summary="Eliminar tarea",
     *     tags={"Tareas"},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="string")),
     *     @OA\Response(response=200, description="Tarea eliminada")
     * )
     */
    public function destroy(string $id)
    {
        $task = Task::findOrFail($id);
        $task->delete();
        return response()->json(['message' => 'Tarea eliminada']);
    }
}
