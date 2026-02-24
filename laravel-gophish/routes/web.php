<?php

use App\Http\Controllers\CampaignController;
use App\Http\Controllers\PhishingController;
use App\Http\Controllers\TemplateController;
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
Route::middleware(['web'])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Campaigns/Index', [
             'campaigns' => \App\Models\Campaign::with('user')->orderBy('created_at', 'desc')->paginate(10)
        ]);
    })->name('dashboard');

    Route::resource('campaigns', CampaignController::class);
    Route::resource('templates', TemplateController::class);
});

// Phishing Routes (Public)
Route::get('/track', [PhishingController::class, 'track'])->name('phishing.track');
Route::get('/', [PhishingController::class, 'landing'])->name('phishing.landing');

Route::get('/', function (\Illuminate\Http\Request $request) {
    if ($request->has('rid')) {
        return app(PhishingController::class)->landing($request);
    }
    return redirect()->route('dashboard');
});

Route::post('/', [PhishingController::class, 'submit'])->name('phishing.submit');
