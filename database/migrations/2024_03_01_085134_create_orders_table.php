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
            $table->uuid();
            $table->foreignId('company_id')->constrained();
            $table->string('model')->nullable(); // e.g Vehicle
            $table->integer('model_id')->nullable(); // e.g 1
            $table->string('account_no')->unique(); // to be used for payment (mpesa)
            $table->double('amount');
            $table->boolean('payment_status')->default(false); // 0: pending, 1: paid
            $table->dateTime('paid_on')->nullable();
            $table->softDeletes();
            $table->timestamps();
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
