<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('service_requests', function (Blueprint $table) {
            $table->enum('quotation_status', ['not_quoted', 'quoted', 'accepted', 'declined'])
                ->default('not_quoted')
                ->after('status');
            $table->decimal('quoted_amount', 12, 2)->nullable()->after('quotation_status');
            $table->text('quotation_notes')->nullable()->after('quoted_amount');
            $table->foreignId('quoted_by')->nullable()->after('quotation_notes')->constrained('users')->nullOnDelete();
            $table->timestamp('quoted_at')->nullable()->after('quoted_by');
        });
    }

    public function down(): void
    {
        Schema::table('service_requests', function (Blueprint $table) {
            $table->dropConstrainedForeignId('quoted_by');
            $table->dropColumn(['quotation_status', 'quoted_amount', 'quotation_notes', 'quoted_at']);
        });
    }
};
