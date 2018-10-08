<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateWebhooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('webhooks', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('client_id')->default(0)->comment('客户端ID');
            $table->unsignedInteger('payment_channel_id')->default(0)->comment('支付渠道ID');
            $table->string('trade_no', 64)->index()->nullable()->comment('交易号');
            $table->unsignedInteger('webhookable_id')->default(0)->comment('webhook id');
            $table->string('webhookable_type')->comment('webhook 类型');
            $table->string('out_trade_no', 64)->nullable()->comment('商户交易号');
            $table->string('channel_trade_no')->nullable()->comment('渠道交易号');
            $table->string('url')->nullable()->comment('通知URL');
            $table->text('context')->nullable()->comment('通知内容');
            $table->unsignedTinyInteger('status')->index()->default(0)->comment('通知结果\n0 待通知\n1 通知成功\n2 通知失败');
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
        Schema::dropIfExists('webhooks');
    }
}
