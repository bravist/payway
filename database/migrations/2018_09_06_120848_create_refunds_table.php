<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateRefundsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('refunds', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('client_id')->default(0)->comment('客户端ID');
            $table->unsignedInteger('payment_channel_id')->default(0)->comment('退款渠道ID');
            $table->unsignedInteger('payment_order_id')->default(0)->comment('支付订单ID');
            $table->string('trade_no', 64)->nullable()->comment('交易号');
            $table->string('refund_no', 64)->unique()->nullable()->comment('退款单号');
            $table->string('channel_refund_no')->nullable()->comment('渠道退款单号');
            $table->unsignedBigInteger('amount')->default(0)->comment('退款金额，单位：分');
            $table->string('reason')->comment('退款原因');
            $table->timestamp('refunded_at')->nullable()->comment('退款完成时间');
            $table->string('status')->index()->default('pending')->comment('退款状态 pending 待退款 processing 退款中 success 退款成功 closed 已关闭');
            $table->string('error')->nullable()->comment('失败原因');
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
        Schema::dropIfExists('refunds');
    }
}
