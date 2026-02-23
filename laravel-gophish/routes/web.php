<?php

use App\Http\Controllers\CampaignController;
use App\Http\Controllers\PhishingController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Admin / Dashboard Routes
// In a real app, you'd wrap this in ['auth', 'verified']
Route::middleware(['web'])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Campaigns/Index', [
             'campaigns' => \App\Models\Campaign::orderBy('created_at', 'desc')->paginate(10)
        ]);
    })->name('dashboard');

    Route::resource('campaigns', CampaignController::class);
});

// Phishing Routes (Public)
// Ideally these should be on a separate domain or distinct path
Route::get('/track', [PhishingController::class, 'track'])->name('phishing.track');
Route::get('/', [PhishingController::class, 'landing'])->name('phishing.landing'); // This might conflict with root redirect.
// Gophish often runs on a separate port or domain.
// Since we are in one app, let's prefix or use domain routing if we could.
// For now, I'll put them under /phish prefix for clarity, OR the user handles it via Nginx.
// But the user said "separate later".
// So let's route them as normal but maybe use a specific path for now to avoid conflict with admin.
// However, the tracking URL in the job is `rtrim($url, '/') . '/track?rid=' ...`.
// If $url is the app URL, it hits `/track`.

// If the user visits `/` without `rid`, they go to dashboard (redirect).
// If they visit `/` WITH `rid`, they go to landing page?
// Let's implement that logic in the root route.

Route::get('/', function (\Illuminate\Http\Request $request) {
    if ($request->has('rid')) {
        return app(PhishingController::class)->landing($request);
    }
    return redirect()->route('dashboard');
});

Route::post('/', [PhishingController::class, 'submit'])->name('phishing.submit');
