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
        Schema::create('catalogue_room_attribute', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('catalogue_room_id');
            $table->uuid('attribute_id');
            $table->uuid('org_id');
            $table->softDeletes();
            $table->timestamps();

//            $table->foreign('catalogue_room_id')->references('id')->on('catalogue_rooms')->onDelete('cascade');
//            $table->foreign('attribute_id')->references('id')->on('attributes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('catalogue_room_attribute');
    }
};
