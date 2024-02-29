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
            $table->string('name'); // e.g. Audi RS7
            $table->foreignId('make_model_id')->constrained();
            $table->foreignId('transmission_type_id')->constrained();
            $table->integer('condition'); // 1: foreign user, 2: local used
            $table->integer('engine_capacity'); // e.g 2500cc
            $table->foreignId('fuel_type_id');
            $table->foreignId('drive_type_id');
            $table->foreignId('body_type_id');
            $table->foreignId('currency_id');
            $table->double('price');
            $table->integer('mileage'); // in km
            $table->integer('yom'); // year of manufacture
            $table->string('color'); // e.g. blue
            $table->integer('horse_power'); // in hp
            $table->integer('torque'); // in Nm
            $table->integer('availability')->default(1); // 1: available, 2: sold, 3: in transit
            $table->text('features'); // e.g. sunroof, leather seats
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
