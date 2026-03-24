<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('campaigns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained('clients')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null'); // Creator
            $table->string('name');
            $table->string('status')->default('Created'); // Queued, In Progress, Completed
            $table->string('url')->nullable(); // Phishing URL base

            $table->unsignedBigInteger('email_template_id')->nullable();
            $table->unsignedBigInteger('landing_page_id')->nullable();
            $table->unsignedBigInteger('sending_profile_id')->nullable();

            $table->timestamp('launch_date')->nullable();
            $table->timestamp('send_by_date')->nullable();
            $table->timestamp('completed_date')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('campaigns');
    }
};
