<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('logs', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedTinyInteger('payment_event_id')->index()->default(0)->comment('日志事件ID');
            $table->unsignedInteger('logger_id')->default(0)->comment('日志多态类型ID');
            $table->string('logger_type')->comment('日志多态类型');
            $table->string('request_url')->nullable()->comment('请求URL');
            $table->text('request')->nullable()->comment('请求内容');
            $table->text('response')->nullable()->comment('响应内容');
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
        Schema::dropIfExists('logs');
    }
}
