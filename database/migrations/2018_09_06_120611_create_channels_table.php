<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateChannelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('channels', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('client_id')->default(0)->comment('客户端ID');
            $table->string('channel', 100)->comment('支付渠道类型');
            $table->string('desc')->comment('支付渠道说明');
            $table->string('return_url')->comment('同步返回URL');
            $table->string('notify_url')->comment('异步通知URL');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('channels');
    }
}
