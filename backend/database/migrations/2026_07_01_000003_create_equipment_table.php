<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('equipment', function (Blueprint $table) {
            $table->id();
            // Nullable self-reference: the JICA inventory ships several accessory
            // line items (desiccator, mortar set, hot plate...) under one parent
            // code, e.g. CESD-02. Accessories share their parent's public catalog
            // entry instead of appearing as separate searchable equipment.
            $table->foreignId('parent_id')->nullable()->constrained('equipment')->cascadeOnDelete();
            $table->foreignId('laboratory_id')->nullable()->constrained('laboratories')->nullOnDelete();
            $table->foreignId('category_id')->nullable()->constrained('equipment_categories')->nullOnDelete();
            $table->foreignId('responsible_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('code');
            $table->string('name_en');
            $table->string('name_lo');
            $table->string('brand')->nullable();
            $table->string('model')->nullable();
            $table->string('serial_number')->nullable();
            $table->string('shipping_country')->nullable();
            $table->string('unit')->nullable();
            $table->unsignedInteger('quantity')->default(1);
            $table->boolean('is_accessory')->default(false);
            $table->text('specification_en')->nullable();
            $table->text('specification_lo')->nullable();
            $table->text('capability_en')->nullable();
            $table->text('capability_lo')->nullable();
            $table->string('photo')->nullable();
            $table->string('manual_file')->nullable();
            $table->enum('availability_status', ['available', 'in_use', 'maintenance', 'retired'])
                ->default('available');
            $table->timestamps();

            $table->index('code');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('equipment');
    }
};
