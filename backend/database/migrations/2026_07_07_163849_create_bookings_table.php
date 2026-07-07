<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_no')->unique();
            $table->foreignId('user_id')->constrained('users')->restrictOnDelete();
            $table->morphs('bookable'); // equipment or laboratory
            $table->text('purpose');
            $table->dateTime('start_at');
            $table->dateTime('end_at');
            $table->enum('status', [
                'pending_advisor',
                'pending_staff',
                'approved',
                'rejected',
                'cancelled',
            ])->default('pending_staff');
            $table->boolean('requires_advisor_approval')->default(false);
            $table->foreignId('advisor_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('advisor_decided_at')->nullable();
            $table->text('advisor_note')->nullable();
            $table->foreignId('staff_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('staff_decided_at')->nullable();
            $table->text('staff_note')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
