<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/projects",
     *     summary="Listar todos los proyectos",
     *     tags={"Proyectos"},
     *     @OA\Parameter(name="status", in="query", @OA\Schema(type="string")),
     *     @OA\Parameter(name="name", in="query", @OA\Schema(type="string")),
     *     @OA\Parameter(name="date_from", in="query", @OA\Schema(type="string", format="date")),
     *     @OA\Parameter(name="date_to", in="query", @OA\Schema(type="string", format="date")),
     *     @OA\Response(response=200, description="Lista de proyectos")
     * )
     */
    public function index(Request $request)
    {
        $query = Project::query();

        if ($request->has('status')) $query->where('status', $request->status);
        if ($request->has('name')) $query->where('name', 'like', '%' . $request->name . '%');
        if ($request->has('date_from') && $request->has('date_to')) {
            $query->whereBetween('created_at', [$request->date_from, $request->date_to]);
        }
        return response()->json($query->orderBy('created_at', 'desc')->paginate(10));
    }

    /**
     * @OA\Post(
     *     path="/api/projects",
     *     summary="Crear nuevo proyecto",
     *     tags={"Proyectos"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "status"},
     *             @OA\Property(property="name", type="string", example="CRM App"),
     *             @OA\Property(property="description", type="string", example="Gestión de clientes"),
     *             @OA\Property(property="status", type="string", enum={"active","inactive"}, example="active")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Proyecto creado")
     * )
     */
    public function store(Request $request)
    {
        $project = Project::create($request->validate());
        return response()->json($project, 201);
    }

    /**
     * @OA\Get(
     *     path="/api/projects/{id}",
     *     summary="Ver un proyecto",
     *     tags={"Proyectos"},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="string")),
     *     @OA\Response(response=200, description="Detalle del proyecto")
     * )
     */
    public function show(string $id)
    {
        $project = Project::with('tasks')->findOrFail($id);
        return response()->json($project);
    }

    /**
     * @OA\Put(
     *     path="/api/projects/{id}",
     *     summary="Actualizar proyecto",
     *     tags={"Proyectos"},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="string")),
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="Nuevo nombre"),
     *             @OA\Property(property="description", type="string", example="Nueva descripción"),
     *             @OA\Property(property="status", type="string", enum={"active","inactive"}, example="inactive")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Proyecto actualizado")
     * )
     */
    public function update(Request $request, string $id)
    {
        $project = Project::findOrFail($id);
        $project->update($request->validate());
        return response()->json($project);
    }

    /**
     * @OA\Delete(
     *     path="/api/projects/{id}",
     *     summary="Eliminar proyecto",
     *     tags={"Proyectos"},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="string")),
     *     @OA\Response(response=200, description="Proyecto eliminado")
     * )
     */
    public function destroy(string $id)
    {
        $project = Project::findOrFail($id);
        $project->delete();
        return response()->json(['message' => 'Proyecto eliminado']);
    }
}
