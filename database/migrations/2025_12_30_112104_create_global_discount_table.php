<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('global_discounts', function (Blueprint $table) {
            $table->id();

            // Discount percentage applied to all orders
            $table->decimal('discount_percentage', 5, 2)->default(0);

            // Whether the global discount is active
            $table->boolean('is_active')->default(true);

            // Optional: track who last updated it
            $table->foreignId('updated_by')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();

            $table->timestamp('last_updated_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('global_discounts');
    }
};
