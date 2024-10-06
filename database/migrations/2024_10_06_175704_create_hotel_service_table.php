<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('hotel_service', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('hotel_id');
            $table->uuid('service_id');
            $table->timestamps();
            $table->softDeletes(); // Soft delete

//            $table->foreign('hotel_id')->references('id')->on('hotels')->onDelete('cascade');
//            $table->foreign('service_id')->references('id')->on('services')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('hotel_service');
    }
};
