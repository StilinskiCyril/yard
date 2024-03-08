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
            $table->uuid();
            $table->integer('channel')->nullable(); // 1: mpesa, 2: card, 3: paypal, 4: offline (e.g. invoice)
            $table->double('amount');
            $table->string('transaction_id')->nullable()->unique();
            $table->dateTime('date');
            $table->string('notes'); // name | msisdn | email | account_no (observe the order seperated by a vertical bar)
            $table->ipAddress('ip')->nullable(); // provider api IP address
            $table->softDeletes();
            $table->timestamps();
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
