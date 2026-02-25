<?php

namespace App\Http\Controllers;

use App\Models\RecipientGroup;
use App\Models\Recipient;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

class RecipientGroupController extends Controller
{
    public function index()
    {
        return Inertia::render('Groups/Index', [
            'groups' => RecipientGroup::withCount('recipients')
                ->orderBy('created_at', 'desc')
                ->paginate(10),
        ]);
    }

    public function create()
    {
        return Inertia::render('Groups/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'recipients' => 'required|array|min:1',
            'recipients.*.email' => 'required|email',
            'recipients.*.first_name' => 'nullable|string|max:255',
            'recipients.*.last_name' => 'nullable|string|max:255',
            'recipients.*.position' => 'nullable|string|max:255',
        ]);

        DB::transaction(function () use ($validated) {
            $group = RecipientGroup::create(['name' => $validated['name']]);

            foreach ($validated['recipients'] as $recipientData) {
                $group->recipients()->create($recipientData);
            }
        });

        return redirect()->route('groups.index')->with('success', 'Group created successfully.');
    }

    public function edit(RecipientGroup $group)
    {
        return Inertia::render('Groups/Edit', [
            'group' => $group->load('recipients'),
        ]);
    }

    public function update(Request $request, RecipientGroup $group)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'recipients' => 'required|array',
            'recipients.*.email' => 'required|email',
            'recipients.*.first_name' => 'nullable|string|max:255',
            'recipients.*.last_name' => 'nullable|string|max:255',
            'recipients.*.position' => 'nullable|string|max:255',
        ]);

        DB::transaction(function () use ($group, $validated) {
            $group->update(['name' => $validated['name']]);

            // Simple sync strategy: Delete all and re-create.
            // Better strategy would be to diff, but for this use case, re-creation is acceptable
            // as we don't track history on the recipient *definition* itself (Result tracks history).

            $group->recipients()->delete();

            foreach ($validated['recipients'] as $recipientData) {
                $group->recipients()->create($recipientData);
            }
        });

        return redirect()->route('groups.index')->with('success', 'Group updated successfully.');
    }

    public function destroy(RecipientGroup $group)
    {
        $group->delete();
        return redirect()->route('groups.index')->with('success', 'Group deleted successfully.');
    }
}
