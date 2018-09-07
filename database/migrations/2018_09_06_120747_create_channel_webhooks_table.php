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
        Schema::create('payment_channel_webhooks', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('payment_channel_id')->default(0)->comment('支付渠道ID');
            $table->string('trade_no', 64)->nullable()->comment('交易号');
            $table->string('out_trade_no', 64)->nullable()->comment('商户交易号');
            $table->string('channel_trade_no')->nullable()->comment('渠道交易号');
            $table->unsignedTinyInteger('channel')->index()->default(0)->comment('支付渠道');
            $table->string('url')->nullable()->comment('通知URL');
            $table->text('context')->nullable()->comment('通知内容');
            $table->string('response')->nullable()->comment('响应内容');
            $table->timestamps();
        });
        DB::statement("ALTER TABLE `payment_channel_webhooks` comment ' 支付渠道异步通知日志'");
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
