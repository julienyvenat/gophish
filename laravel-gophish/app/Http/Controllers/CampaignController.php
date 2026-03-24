<?php

namespace App\Http\Controllers;

use App\Jobs\SendPhishingEmail;
use App\Models\Campaign;
use App\Models\CampaignEvent;
use App\Models\CampaignResult;
use App\Models\EmailTemplate;
use App\Models\LandingPage;
use App\Models\RecipientGroup;
use App\Models\SendingProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class CampaignController extends Controller
{
    public function index()
    {
        return Inertia::render('Campaigns/Index', [
            'campaigns' => Campaign::with('user')->orderBy('created_at', 'desc')->paginate(10),
        ]);
    }

    public function create()
    {
        return Inertia::render('Campaigns/Create', [
            'templates' => EmailTemplate::all(),
            'pages' => LandingPage::all(),
            'groups' => RecipientGroup::all(),
            'profiles' => SendingProfile::all(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email_template_id' => 'required|exists:email_templates,id',
            'landing_page_id' => 'required|exists:landing_pages,id',
            'sending_profile_id' => 'required|exists:sending_profiles,id',
            'recipient_group_ids' => 'required|array',
            'recipient_group_ids.*' => 'exists:recipient_groups,id',
            'launch_date' => 'nullable|date',
            'send_by_date' => 'nullable|date|after:launch_date',
            'url' => 'required|url',
        ]);

        $campaign = Campaign::create([
            'name' => $validated['name'],
            'email_template_id' => $validated['email_template_id'],
            'landing_page_id' => $validated['landing_page_id'],
            'sending_profile_id' => $validated['sending_profile_id'],
            'launch_date' => $validated['launch_date'] ?? now(),
            'send_by_date' => $validated['send_by_date'],
            'url' => $validated['url'],
            'status' => 'Queued',
            'user_id' => auth()->id(),
        ]);

        CampaignEvent::create([
            'campaign_id' => $campaign->id,
            'time' => now(),
            'message' => 'Campaign Created',
        ]);

        // Process Recipients
        $groups = RecipientGroup::whereIn('id', $validated['recipient_group_ids'])->with('recipients')->get();
        foreach ($groups as $group) {
            foreach ($group->recipients as $recipient) {
                // Avoid duplicates if needed, but for now just add all
                $rid = Str::random(16);

                $result = CampaignResult::create([
                    'campaign_id' => $campaign->id,
                    'email' => $recipient->email,
                    'first_name' => $recipient->first_name,
                    'last_name' => $recipient->last_name,
                    'position' => $recipient->position,
                    'status' => 'Scheduled',
                    'rid' => $rid,
                    'send_date' => $campaign->launch_date, // Or calculated distributed time
                ]);

                // Dispatch Job
                // Calculate delay if send_by_date is set (simple distribution logic omitted for brevity)
                $delay = $campaign->launch_date > now() ? $campaign->launch_date : now();

                SendPhishingEmail::dispatch($result->id)->delay($delay);
            }
        }

        return redirect()->route('campaigns.index')->with('success', 'Campaign created successfully.');
    }

    public function show(Campaign $campaign)
    {
        return Inertia::render('Campaigns/Show', [
            'campaign' => $campaign->load(['results', 'events', 'emailTemplate', 'landingPage', 'sendingProfile']),
        ]);
    }
}
