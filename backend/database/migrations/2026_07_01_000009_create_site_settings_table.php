<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Singleton table (always a single row with id = 1).
        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            $table->string('contact_email')->nullable();
            $table->string('contact_phone')->nullable();
            $table->string('address_en')->nullable();
            $table->string('address_lo')->nullable();
            $table->string('facebook_url')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('site_settings');
    }
};
