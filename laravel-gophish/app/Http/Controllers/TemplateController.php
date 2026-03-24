<?php

namespace App\Http\Controllers;

use App\Models\EmailTemplate;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TemplateController extends Controller
{
    public function index()
    {
        return Inertia::render('Templates/Index', [
            'templates' => EmailTemplate::orderBy('created_at', 'desc')->paginate(10),
        ]);
    }

    public function create()
    {
        return Inertia::render('Templates/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'html' => 'nullable|string',
            'text' => 'nullable|string',
        ]);

        EmailTemplate::create($validated);

        return redirect()->route('templates.index')->with('success', 'Template created successfully.');
    }

    public function edit(EmailTemplate $template)
    {
        return Inertia::render('Templates/Edit', [
            'template' => $template,
        ]);
    }

    public function update(Request $request, EmailTemplate $template)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'html' => 'nullable|string',
            'text' => 'nullable|string',
        ]);

        $template->update($validated);

        return redirect()->route('templates.index')->with('success', 'Template updated successfully.');
    }

    public function destroy(EmailTemplate $template)
    {
        $template->delete();
        return redirect()->route('templates.index')->with('success', 'Template deleted successfully.');
    }
}
