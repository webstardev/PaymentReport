<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKeyInsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('key_ins', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->foreignId('user_id');
            $table->foreignId('brand_id')->constraind('brains');
            $table->timestamp('date');
            $table->float('sum');
            $table->string('payment_method');
            $table->string('received')->nullable();
            $table->string('comments');
            $table->string('currency')->nullable();
            $table->string('country')->nullable();
            $table->string('expenses_type')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('key_ins');
    }
}
