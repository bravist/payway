<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateChannelPayWaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_channel_pay_ways', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('payment_channel_id')->default(0)->comment('支付渠道ID');
            $table->string('way', 10)->comment('支付方式');
            $table->string('merchant_id', 45)->comment('渠道商户号');
            $table->string('app_id', 45)->comment('支付网关APP_ID');
            $table->string('app_secret')->comment('支付主体APP密钥');
            $table->string('certficate')->comment('支付主体证书');
            $table->string('key')->comment('支付主体密钥');
            $table->float('refund_rate')->comment('退款费率');
            $table->timestamps();
        });
        DB::statement("ALTER TABLE `payment_channel_pay_ways` comment '支付渠道付款方式'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('channel_pay_ways');
    }
}
