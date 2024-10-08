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
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('org_id');
            $table->string('name', 255);
            $table->string('email', 255);
            $table->string('phone', 255);
            $table->string('address', 255);
            $table->string('password', 255);
            $table->string('cccd', 255);
            $table->tinyInteger('is_active');
            $table->uuid('rank_id');
            $table->string('remember_token', 255)->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->tinyInteger('type');
            $table->timestamps();
            $table->softDeletes(); // Soft delete

//            $table->foreign('rank_id')->references('id')->on('ranks');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
