<?php

namespace App\Http\Controllers;

use App\Models\CampaignEvent;
use App\Models\CampaignResult;
use App\Models\LandingPage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PhishingController extends Controller
{
    public function track(Request $request)
    {
        $rid = $request->query('rid');
        if (!$rid) {
            return response()->noContent();
        }

        $result = CampaignResult::where('rid', $rid)->first();
        if ($result) {
            if ($result->status == 'Sent') {
                $result->update(['status' => 'Opened', 'ip' => $request->ip(), 'user_agent' => $request->userAgent()]);
            }

            CampaignEvent::create([
                'campaign_id' => $result->campaign_id,
                'email' => $result->email,
                'time' => now(),
                'message' => 'Email Opened',
                'details' => json_encode(['ip' => $request->ip(), 'user_agent' => $request->userAgent()]),
            ]);
        }

        // Return 1x1 transparent gif
        return response(base64_decode('R0lGODlhAQABAJAAAP8AAAAAACH5BAUQAAAALAAAAAABAAEAAAICBAEAOw=='))
            ->header('Content-Type', 'image/gif')
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate');
    }

    public function landing(Request $request)
    {
        $rid = $request->query('rid');
        if (!$rid) {
            return abort(404);
        }

        $result = CampaignResult::with('campaign.landingPage')->where('rid', $rid)->first();
        if (!$result) {
            return abort(404);
        }

        if ($result->status != 'Clicked' && $result->status != 'Submitted') {
            $result->update(['status' => 'Clicked', 'ip' => $request->ip(), 'user_agent' => $request->userAgent()]);

            CampaignEvent::create([
                'campaign_id' => $result->campaign_id,
                'email' => $result->email,
                'time' => now(),
                'message' => 'Clicked Link',
                'details' => json_encode(['ip' => $request->ip(), 'user_agent' => $request->userAgent()]),
            ]);
        }

        $page = $result->campaign->landingPage;
        if (!$page) {
             return abort(404);
        }

        // Render the HTML.
        // We might need to inject the RID into the form action so submission works.
        // Gophish does this by replacing the form action or assuming post to same URL.
        // Since the URL has ?rid=xyz, posting to empty action "" should work.

        return response($page->html);
    }

    public function submit(Request $request)
    {
        $rid = $request->query('rid');
        if (!$rid) {
            // Check referer or hidden field if query param is lost (depending on how form action is set)
            $rid = $request->input('rid');
        }

        if (!$rid) {
             return abort(404);
        }

        $result = CampaignResult::with('campaign.landingPage')->where('rid', $rid)->first();
        if ($result) {
            $result->update(['status' => 'Submitted', 'ip' => $request->ip()]);

            $capturedData = $request->except(['password', '_token']); // Never store passwords unless explicitly enabled? Gophish has a setting.
            // Check page setting
            if ($result->campaign->landingPage->capture_passwords) {
                $capturedData = $request->except(['_token']);
            } else {
                 // Remove password fields just in case
                 foreach ($request->all() as $key => $value) {
                     if (stripos($key, 'password') !== false) {
                         unset($capturedData[$key]);
                     }
                 }
            }

            CampaignEvent::create([
                'campaign_id' => $result->campaign_id,
                'email' => $result->email,
                'time' => now(),
                'message' => 'Submitted Data',
                'details' => json_encode(['payload' => $capturedData]),
            ]);

            if ($result->campaign->landingPage->redirect_url) {
                return redirect($result->campaign->landingPage->redirect_url);
            }
        }

        return redirect('/');
    }
}
