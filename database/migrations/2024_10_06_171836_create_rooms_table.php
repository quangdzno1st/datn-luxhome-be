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
        Schema::create('rooms', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('catalogue_room_id');
            $table->tinyInteger('status');
            $table->uuid('org_id');
            $table->timestamps();
            $table->softDeletes(); // Soft delete

//            $table->foreign('catalogue_room_id')->references('id')->on('catalogue_rooms')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
