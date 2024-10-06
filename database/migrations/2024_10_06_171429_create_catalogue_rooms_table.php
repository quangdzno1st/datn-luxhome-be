<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('catalogue_rooms', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('hotel_id');
            $table->string('name', 255);
            $table->double('price');
            $table->string('description', 255);
            $table->string('image', 255);
            $table->bigInteger('view');
            $table->integer('like');
            $table->tinyInteger('status');
            $table->uuid('org_id');
            $table->timestamps();
            $table->softDeletes();

//            $table->foreign('hotel_id')->references('id')->on('hotels')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('catalogue_rooms');
    }
};
