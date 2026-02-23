<?php

namespace App\Jobs;

use App\Models\CampaignEvent;
use App\Models\CampaignResult;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendPhishingEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $resultId;

    /**
     * Create a new job instance.
     */
    public function __construct($resultId)
    {
        $this->resultId = $resultId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $result = CampaignResult::with(['campaign.emailTemplate', 'campaign.sendingProfile'])->find($this->resultId);

        if (!$result) {
            Log::error("CampaignResult not found: {$this->resultId}");
            return;
        }

        $campaign = $result->campaign;
        $template = $campaign->emailTemplate;
        $profile = $campaign->sendingProfile;

        if (!$template || !$profile) {
            $result->update(['status' => 'Error']);
            Log::error("Missing template or profile for campaign: {$campaign->id}");
            return;
        }

        try {
            $result->update(['status' => 'Sending']);

            // Replace placeholders
            // {{.FirstName}}, {{.LastName}}, {{.Position}}, {{.Email}}, {{.URL}}
            // {{.TrackingURL}}, {{.Tracker}}

            $url = $campaign->url;
            $trackingUrl = rtrim($url, '/') . '/track?rid=' . $result->rid;
            $phishingUrl = rtrim($url, '/') . '?rid=' . $result->rid;

            $replacements = [
                '{{.FirstName}}' => $result->first_name,
                '{{.LastName}}' => $result->last_name,
                '{{.Position}}' => $result->position,
                '{{.Email}}' => $result->email,
                '{{.URL}}' => $phishingUrl,
                '{{.TrackingURL}}' => $trackingUrl,
                '{{.Tracker}}' => '<img src="' . $trackingUrl . '" style="display:none" alt="" />',
            ];

            $subject = str_replace(array_keys($replacements), array_values($replacements), $template->subject);
            $html = str_replace(array_keys($replacements), array_values($replacements), $template->html);
            $text = str_replace(array_keys($replacements), array_values($replacements), $template->text);

            // Append tracker to HTML if not present
            if (strpos($html, '{{.Tracker}}') === false && strpos($html, '<img src="' . $trackingUrl . '"') === false) {
                 $html .= $replacements['{{.Tracker}}'];
            }

            // Configure Mailer
            // In a real app, we would configure the mailer dynamically based on $profile (SMTP settings).
            // For this conversion, since Laravel's config is static by default, we'd need to use a library like
            // verifying dynamic mailer config or setting it on the fly.
            // For now, let's assume the default mailer is used or we log the email content.
            // Implementing dynamic SMTP in Laravel requires setting config(['mail.mailers.smtp_dynamic' => ...]) and using Mail::mailer('smtp_dynamic')

            $smtpConfig = [
                'transport' => 'smtp',
                'host' => $profile->host,
                'port' => 587, // Default or parse from host:port
                'encryption' => 'tls', // Default
                'username' => $profile->username,
                'password' => $profile->password,
                'timeout' => null,
            ];

            // Handle host:port
            if (strpos($profile->host, ':') !== false) {
                [$host, $port] = explode(':', $profile->host);
                $smtpConfig['host'] = $host;
                $smtpConfig['port'] = $port;
            }

            config(['mail.mailers.dynamic_smtp' => $smtpConfig]);

            Mail::mailer('dynamic_smtp')->send([], [], function ($message) use ($profile, $result, $subject, $html, $text) {
                $message->to($result->email, $result->first_name . ' ' . $result->last_name)
                        ->subject($subject)
                        ->from($profile->from_address, $profile->display_name);

                if ($html) {
                    $message->html($html);
                }
                if ($text) {
                    $message->text($text);
                }

                // Add Custom Headers
                if ($profile->headers) {
                    foreach ($profile->headers as $key => $value) {
                         $message->getHeaders()->addTextHeader($key, $value);
                    }
                }
            });

            $result->update(['status' => 'Sent', 'send_date' => now()]);

            CampaignEvent::create([
                'campaign_id' => $campaign->id,
                'email' => $result->email,
                'time' => now(),
                'message' => 'Email Sent',
            ]);

        } catch (\Exception $e) {
            $result->update(['status' => 'Error']);
            CampaignEvent::create([
                'campaign_id' => $campaign->id,
                'email' => $result->email,
                'time' => now(),
                'message' => 'Error Sending Email',
                'details' => $e->getMessage(),
            ]);
            Log::error("Error sending phishing email: " . $e->getMessage());
            // We might want to rethrow to retry via Queue system
            // throw $e;
        }
    }
}
