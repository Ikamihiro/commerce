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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('external_id')->nullable();
            $table->json('data')->nullable();
            $table->string('status')->default('pending');
            $table->string('amount');
            $table->integer('installments')->default(1);
            $table->string('type');
            $table->foreignId('order_id')->constrained('orders');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
