<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Singleton table (always a single row with id = 1), mirroring
     * site_settings — backs the editable body copy on the public About page.
     */
    public function up(): void
    {
        Schema::create('about_content', function (Blueprint $table) {
            $table->id();
            $table->string('title_en')->nullable();
            $table->string('title_lo')->nullable();
            $table->text('background_en')->nullable();
            $table->text('background_lo')->nullable();
            $table->text('vision_en')->nullable();
            $table->text('vision_lo')->nullable();
            $table->text('mission_en')->nullable();
            $table->text('mission_lo')->nullable();
            $table->string('objective1_en')->nullable();
            $table->string('objective1_lo')->nullable();
            $table->string('objective2_en')->nullable();
            $table->string('objective2_lo')->nullable();
            $table->string('objective3_en')->nullable();
            $table->string('objective3_lo')->nullable();
            $table->string('objective4_en')->nullable();
            $table->string('objective4_lo')->nullable();
            $table->string('org_director_en')->nullable();
            $table->string('org_director_lo')->nullable();
            $table->string('org_deputy_director_en')->nullable();
            $table->string('org_deputy_director_lo')->nullable();
            $table->string('org_admin_en')->nullable();
            $table->string('org_admin_lo')->nullable();
            $table->string('org_technical_en')->nullable();
            $table->string('org_technical_lo')->nullable();
            $table->string('org_innovation_en')->nullable();
            $table->string('org_innovation_lo')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('about_content');
    }
};
