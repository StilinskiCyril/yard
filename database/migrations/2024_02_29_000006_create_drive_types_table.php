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
        Schema::create('drive_types', function (Blueprint $table) {
            $table->id();
            $table->uuid();
            $table->string('type')->unique(); // e.g FWD (Front Wheel Drive), RWD (Rear Wheel Drive), 4WD (Four Wheel Drive), AWD (All Wheel Drive)
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('drive_types');
    }
};
