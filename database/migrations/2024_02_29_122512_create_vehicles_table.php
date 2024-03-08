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
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->uuid();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('company_id')->constrained();
            $table->string('name'); // e.g. Audi RS7
            $table->foreignId('make_model_id')->constrained();
            $table->foreignId('transmission_type_id')->constrained();
            $table->foreignId('vehicle_condition_id')->constrained();
            $table->integer('engine_capacity'); // e.g 2500cc
            $table->foreignId('fuel_type_id')->constrained();
            $table->foreignId('drive_type_id')->constrained();
            $table->foreignId('body_type_id')->constrained();
            $table->foreignId('drive_setup_id')->constrained();
            $table->foreignId('currency_id')->constrained();
            $table->double('price');
            $table->integer('mileage'); // in km
            $table->integer('yom'); // year of manufacture
            $table->string('color'); // e.g. blue, gray
            $table->integer('horse_power'); // in hp
            $table->integer('torque'); // in Nm
            $table->string('cover_photo_url');
            $table->string('youtube_url')->nullable();
            $table->text('features'); // e.g. sunroof, leather seats
            $table->boolean('is_featured')->default(false); // shown at the top of the list
            $table->integer('availability')->default(1); // 1: available, 2: sold, 3: in transit
            $table->boolean('status')->default(false); // 0: pending, 1: approved (only approved vehicles are shown on the website)
            $table->boolean('payment_status')->default(false); // 0: pending, 1: paid
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
