<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Program;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApplicationController extends Controller
{
    public function index(Request $request)
    {
        $query = Application::with(['user:id,name,email', 'program:id,name,start_date,end_date']);

        // Filtros
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('program_id')) {
            $query->where('program_id', $request->program_id);
        }

        if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $applications = $query->orderBy('created_at', 'desc')->paginate(15);

        return response()->json([
            'ok' => true,
            'data' => $applications
        ]);
    }

    public function myApplications()
    {
        $applications = Application::with(['program:id,name,description,start_date,end_date,status'])
                                  ->where('user_id', Auth::id())
                                  ->orderBy('created_at', 'desc')
                                  ->paginate(15);

        return response()->json([
            'ok' => true,
            'data' => $applications
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'program_id' => 'required|exists:programs,id',
            'motivation' => 'nullable|string|max:1000',
        ]);

        $program = Program::find($validated['program_id']);

        // Verificar se o programa aceita candidaturas
        if (!$program->isAvailableForApplication()) {
            return response()->json([
                'ok' => false,
                'message' => 'Este programa não está disponível para candidaturas no momento'
            ], 422);
        }

        // Verificar se já se candidatou
        $existingApplication = Application::where('user_id', Auth::id())
                                         ->where('program_id', $validated['program_id'])
                                         ->first();

        if ($existingApplication) {
            return response()->json([
                'ok' => false,
                'message' => 'Você já se candidatou a este programa'
            ], 422);
        }

        // Verificar limite de candidatos
        if ($program->hasReachedMaxCandidates()) {
            return response()->json([
                'ok' => false,
                'message' => 'Este programa já atingiu o limite máximo de candidatos'
            ], 422);
        }

        $application = Application::create([
            'user_id' => Auth::id(),
            'program_id' => $validated['program_id'],
            'motivation' => $validated['motivation'] ?? null,
        ]);

        $application->load(['program:id,name,description', 'user:id,name,email']);

        return response()->json([
            'ok' => true,
            'data' => $application,
            'message' => 'Candidatura submetida com sucesso'
        ], 201);
    }

    public function show($id)
    {
        $application = Application::with(['user:id,name,email', 'program'])
                                 ->find($id);

        if (!$application) {
            return response()->json([
                'ok' => false,
                'message' => 'Candidatura não encontrada'
            ], 404);
        }

        return response()->json([
            'ok' => true,
            'data' => $application
        ]);
    }

    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,approved,rejected',
        ]);

        $application = Application::find($id);

        if (!$application) {
            return response()->json([
                'ok' => false,
                'message' => 'Candidatura não encontrada'
            ], 404);
        }

        $application->update(['status' => $validated['status']]);
        $application->load(['user:id,name,email', 'program:id,name']);

        return response()->json([
            'ok' => true,
            'data' => $application,
            'message' => 'Status da candidatura atualizado com sucesso'
        ]);
    }

    public function destroy($id)
    {
        $application = Application::where('id', $id)
                                 ->where('user_id', Auth::id())
                                 ->first();

        if (!$application) {
            return response()->json([
                'ok' => false,
                'message' => 'Candidatura não encontrada ou não autorizada'
            ], 404);
        }

        $application->delete();

        return response()->json([
            'ok' => true,
            'message' => 'Candidatura cancelada com sucesso'
        ]);
    }
}
