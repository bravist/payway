<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateChannelWebhooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('channel_webhooks', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('client_id')->default(0)->comment('客户端ID');
            $table->unsignedInteger('payment_channel_id')->index()->default(0)->comment('支付渠道ID');
            $table->unsignedInteger('webhookable_id')->default(0)->comment('webhook id');
            $table->string('webhookable_type')->comment('webhook 类型');
            $table->string('trade_no', 64)->index()->nullable()->comment('交易号');
            $table->string('out_trade_no', 64)->nullable()->comment('商户交易号');
            $table->string('channel_trade_no')->nullable()->comment('渠道交易号');
            $table->string('channel')->index()->comment('支付渠道');
            $table->text('context')->nullable()->comment('通知内容');
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
        Schema::dropIfExists('channel_webhooks');
    }
}
