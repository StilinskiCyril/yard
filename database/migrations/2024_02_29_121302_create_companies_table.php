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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->uuid();
            $table->string('name')->unique();
            $table->string('msisdn')->unique();
            $table->string('email')->unique();
            $table->integer('no_of_employees');
            $table->string('address')->nullable();
            $table->string('website_url')->nullable();
            $table->foreignId('county_id')->nullable()->constrained();
            $table->text('about')->nullable();
            $table->string('logo_url')->nullable();
            $table->string('kyc_doc_url')->nullable();
            $table->string('account_no')->unique(); // to be used for payments
            $table->double('wallet_balance')->default(0);
            $table->integer('status')->default(1); // 0: inactive, 1: active, 2:suspended
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
