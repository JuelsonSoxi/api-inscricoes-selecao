<?php

namespace App\Http\Controllers;

use App\Models\Program;
use Illuminate\Http\Request;

class ProgramController extends Controller
{
    public function index(Request $request)
    {
        $query = Program::query();

        // Filtros
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('available')) {
            $query->availableForApplication();
        }

        $programs = $query->orderBy('created_at', 'desc')->paginate(15);

        return response()->json([
            'ok' => true,
            'data' => $programs
        ]);
    }

    public function show($id)
    {
        $program = Program::with(['applications.user:id,name,email'])->find($id);

        if (!$program) {
            return response()->json([
                'ok' => false,
                'message' => 'Programa não encontrado'
            ], 404);
        }

        return response()->json([
            'ok' => true,
            'data' => $program
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
            'status' => 'required|in:active,inactive',
            'max_candidates' => 'nullable|integer|min:1',
        ]);

        $program = Program::create($validated);

        return response()->json([
            'ok' => true,
            'data' => $program,
            'message' => 'Programa criado com sucesso'
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $program = Program::find($id);

        if (!$program) {
            return response()->json([
                'ok' => false,
                'message' => 'Programa não encontrado'
            ], 404);
        }

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'start_date' => 'sometimes|date',
            'end_date' => 'sometimes|date|after:start_date',
            'status' => 'sometimes|in:active,inactive',
            'max_candidates' => 'nullable|integer|min:1',
        ]);

        $program->update($validated);

        return response()->json([
            'ok' => true,
            'data' => $program,
            'message' => 'Programa atualizado com sucesso'
        ]);
    }

    public function destroy($id)
    {
        $program = Program::find($id);

        if (!$program) {
            return response()->json([
                'ok' => false,
                'message' => 'Programa não encontrado'
            ], 404);
        }

        $program->delete();

        return response()->json([
            'ok' => true,
            'message' => 'Programa deletado com sucesso'
        ]);
    }
}