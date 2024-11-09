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
        Schema::create('hotels', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name', 255);
            $table->string('location', 255);
            $table->integer('quantity_of_room');
            $table->tinyInteger('star');
            $table->uuid('city_id');
            $table->string('phone', 255);
            $table->string('email', 255);
            $table->boolean('status')->default(true);
            $table->integer('quantity_floor');
            $table->timestamps();
            $table->softDeletes(); // Soft delete

//            $table->foreign('city_id')->references('id')->on('cities')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('hotels');
    }
};
