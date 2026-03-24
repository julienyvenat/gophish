<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('campaign_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campaign_id')->constrained('campaigns')->onDelete('cascade');
            // We link to the original recipient, but we might want to snapshot data.
            // For now, let's link to the Recipients table, but typically Gophish copies data to the results table
            // so if the group is modified, the campaign results aren't affected.
            // I will store the email and name here as well to preserve history.
            $table->string('email');
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('position')->nullable();

            $table->string('status')->default('Scheduled'); // Scheduled, Sending, Sent, Error, Opened, Clicked, Submitted
            $table->string('rid')->unique(); // Unique ID for tracking (the 'rid' parameter)

            $table->ipAddress('ip')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();

            $table->timestamp('send_date')->nullable();
            $table->boolean('reported')->default(false);

            $table->timestamps();

            // Index for fast lookups by RID
            $table->index('rid');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('campaign_results');
    }
};
