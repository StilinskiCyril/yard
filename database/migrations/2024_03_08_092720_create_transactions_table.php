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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->uuid();
            $table->foreignId('payment_id')->nullable()->constrained();
            $table->integer('channel')->nullable(); // 1: mpesa, 2: card, 3: paypal, 4: offline (e.g. invoice)
            $table->double('amount'); // will have positives (credit) and negatives (debit)
            $table->string('type'); // debit, credit
            $table->string('model')->nullable(); // e.g Company
            $table->integer('model_id')->nullable(); // e.g 1
            $table->string('notes');
            $table->string('desc')->default('Successful');
            $table->boolean('status')->default(true); // 1 = accepted, 0 = pending
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
