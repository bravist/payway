<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_events', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique()->comment('事件名');
            $table->string('desc')->nullable()->comment('事件描述');
            $table->timestamps();
        });
        DB::statement("ALTER TABLE `payment_events` comment '操作事件'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('events');
    }
}
