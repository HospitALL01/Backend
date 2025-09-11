<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // database/migrations/..._create_ambulances_table.php
public function up(): void
{
    Schema::create('ambulances', function (Blueprint $table) {
        $table->id();
        $table->string('hospital_name');
        $table->decimal('latitude', 10, 7); // For storing GPS location
        $table->decimal('longitude', 10, 7);
        $table->string('driver_name');
        $table->string('driver_phone');
        $table->enum('status', ['Available', 'Busy', 'On-Trip'])->default('Available');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ambulances');
    }
};
