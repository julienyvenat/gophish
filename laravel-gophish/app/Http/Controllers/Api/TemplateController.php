<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\EmailTemplate;
use Illuminate\Http\Request;

class TemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return EmailTemplate::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'subject' => 'nullable|string',
            'text' => 'nullable|string',
            'html' => 'nullable|string',
        ]);

        // If using multi-tenancy, the BelongsToClient trait handles client_id
        // via Auth::user()->client_id, assuming API authentication is set up.
        // For now, we assume standard auth or client context via middleware if implemented.
        // If not, we might need to set a default client for testing.

        $template = new EmailTemplate($validated);
        // Fallback for no auth/client context in simple tests
        if (auth()->check() && auth()->user()->client_id) {
             // Handled by trait
        } else {
             // For testing without auth/client, we might need to bypass or set manually.
             // But the trait enforces "creating".
             // Let's ensure a client exists or let the trait fail if strict.
             // If we are in "open" mode, maybe disable the scope?
             // But better to stick to the plan.
             // We will assume the user (if any) has a client.
             // If no user, the trait might fail or leave client_id null if nullable in DB but model events require it?
             // Migration says: foreignId('client_id')->constrained(). So it's required.
             // We need an authenticated user with a client to create templates.
        }

        // For this demo, let's allow null client_id if not authenticated, IF the db column allows it?
        // Migration: $table->foreignId('client_id')->constrained() -> Not nullable by default.
        // So we MUST be authenticated to create templates.

        $template->save();

        return response()->json($template, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(EmailTemplate $template)
    {
        return $template;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, EmailTemplate $template)
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'subject' => 'nullable|string',
            'text' => 'nullable|string',
            'html' => 'nullable|string',
        ]);

        $template->update($validated);

        return $template;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EmailTemplate $template)
    {
        $template->delete();
        return response()->noContent();
    }
}
