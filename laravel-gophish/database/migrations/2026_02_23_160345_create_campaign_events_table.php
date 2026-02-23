<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('campaign_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campaign_id')->constrained('campaigns')->onDelete('cascade');
            $table->string('email')->nullable(); // Who did it
            $table->timestamp('time');
            $table->string('message'); // e.g., "Email Opened", "Clicked Link"
            $table->text('details')->nullable(); // JSON payload or extra info
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('campaign_events');
    }
};
