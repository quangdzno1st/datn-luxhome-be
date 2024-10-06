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
    public function up()
    {
        Schema::create('vouchers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('code', 255);
            $table->string('description', 255);
            $table->tinyInteger('status')->default(1);
            $table->integer('quantity');
            $table->tinyInteger('discount_type');
            $table->double('discount_value');
            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();
            $table->double('min_price');
            $table->double('max_price')->comment('tiền tối đa được giảm');
            $table->uuid('rank_id')->nullable();
            $table->uuid('conditional_rank')->nullable();
            $table->double('conditional_total_amount')->nullable();
            $table->timestamps();
            $table->softDeletes(); // Soft delete
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vouchers');
    }
};
