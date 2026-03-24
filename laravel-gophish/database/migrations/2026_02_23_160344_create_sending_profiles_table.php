<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sending_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained('clients')->onDelete('cascade');
            $table->string('name');
            $table->string('interface_type')->default('SMTP'); // SMTP
            $table->string('from_address');
            $table->string('display_name')->nullable();
            $table->string('host');
            $table->string('username')->nullable();
            $table->string('password')->nullable();
            $table->boolean('ignore_cert_errors')->default(false);
            $table->json('headers')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sending_profiles');
    }
};
