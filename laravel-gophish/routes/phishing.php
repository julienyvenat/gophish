<?php

use App\Http\Controllers\PhishingController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Phishing Routes
|--------------------------------------------------------------------------
|
| These routes handle the public-facing phishing infrastructure:
| - Tracking pixels
| - Landing pages
| - Form submissions
|
| They can be extracted to a separate application or served on a distinct domain.
*/

Route::get('/track', [PhishingController::class, 'track'])->name('phishing.track');

// Handle landing page view
Route::get('/', [PhishingController::class, 'landing'])->name('phishing.landing');

// Handle form submission
Route::post('/', [PhishingController::class, 'submit'])->name('phishing.submit');

// Optional: specific campaign path if needed
// Route::get('/{campaign_id}', [PhishingController::class, 'landing_by_id']);
