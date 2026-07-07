<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('laboratories', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name_en');
            $table->string('name_lo');
            $table->text('description_en')->nullable();
            $table->text('description_lo')->nullable();
            $table->text('safety_rules_en')->nullable();
            $table->text('safety_rules_lo')->nullable();
            $table->unsignedSmallInteger('location_no')->nullable();
            $table->string('building')->nullable();
            $table->string('floor')->nullable();
            $table->string('room_name')->nullable();
            $table->string('photo')->nullable();
            $table->foreignId('responsible_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laboratories');
    }
};
