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
        Schema::create('channel_pay_ways', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('payment_channel_id')->default(0)->comment('支付渠道ID');
            $table->string('way', 100)->comment('支付方式');
            $table->string('merchant_id', 45)->comment('渠道商户号');
            $table->string('app_id', 45)->comment('支付网关APP_ID');
            $table->string('app_secret')->comment('支付主体APP密钥');
            $table->json('wx_certs')->nullable()->comment('微信支付API证书');
            $table->float('refund_rate')->nullable()->comment('退款费率');
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
        Schema::dropIfExists('channel_pay_ways');
    }
}
