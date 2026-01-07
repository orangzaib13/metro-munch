<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
        $table->string('order_number')->unique();
        $table->foreignId('branch_id')->constrained()->onDelete('cascade');

        // Link to customer table
        $table->foreignId('customer_id')->constrained()->onDelete('cascade');

        $table->enum('type', ['delivery', 'pickup'])->default('delivery');

        $table->string('area')->nullable(); // optional, can store area name
        $table->enum('status', ['pending', 'in-process', 'dispatched', 'completed', 'cancelled'])->default('pending');

        $table->decimal('subtotal', 12, 2);
        $table->decimal('tax', 12, 2)->default(0);
        $table->decimal('discount', 12, 2)->default(0);
        $table->decimal('delivery_fee', 12, 2)->default(0);
        $table->text('delivery_address')->nullable();
        $table->decimal('total', 12, 2);
        $table->enum('payment_status', ['pending', 'paid', 'failed'])->default('pending');
        $table->text('notes')->nullable();

        $table->timestamp('placed_at')->useCurrent();
        $table->timestamp('completed_at')->nullable();
        $table->timestamps();

        // Indexes
        $table->index('order_number');
        $table->index('status');
        $table->index('placed_at');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
