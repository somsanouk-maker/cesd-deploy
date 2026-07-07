<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name_en');
            $table->string('name_lo');
            $table->enum('category', [
                'testing',
                'inspection',
                'performance_test',
                'joint_rd',
                'consulting',
                'training',
                'facility_booking',
            ])->default('testing');
            $table->text('description_en')->nullable();
            $table->text('description_lo')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
