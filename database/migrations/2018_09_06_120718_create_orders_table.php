<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_orders', function (Blueprint $table) {
            $table->increments('id');
            $table->string('trade_no', 64)->unique()->nullable()->comment('交易号');
            $table->string('out_trade_no', 64)->unique()->nullable()->comment('商户交易号');
            $table->unsignedInteger('client_id')->default(0)->comment('客户端ID');
            $table->unsignedInteger('payment_channel_id')->default(0)->comment('支付渠道ID');
            $table->unsignedTinyInteger('channel')->index()->default(0)->comment('支付渠道');
            $table->unsignedTinyInteger('payway')->index()->default(0)->comment('支付方式');
            $table->string('subject')->comment('订单标题');
            $table->unsignedBigInteger('amount')->default(0)->comment('订单金额，单位：分');
            $table->string('body')->nullable()->comment('订单描述');
            $table->string('detail')->nullable()->comment('详细信息');
            $table->json('extra')->nullable()->comment('附加信息');
            $table->string('buyer')->comment('付款人');
            $table->string('seller')->comment('收款人');
            $table->timestamp('pay_at')->nullable()->comment('支付时间');
            $table->timestamp('paid_at')->nullable()->comment('支付完成时间');
            $table->timestamp('expired_at')->index()->nullable()->comment('订单过期时间');
            $table->unsignedTinyInteger('status')->index()->default(0)->comment('状态\n0 待支付\n1 支付成功\n2 支付失败');
            $table->timestamps();
            $table->unique('unique_order', ['trade_no', 'out_trade_no', 'payment_channel_id', 'status']);
        });
        DB::statement("ALTER TABLE `orders` comment '支付订单'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
