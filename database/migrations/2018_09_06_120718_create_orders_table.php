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
            $table->string('out_trade_no', 64)->nullable()->comment('商户交易号');
            $table->unsignedInteger('client_id')->default(0)->comment('客户端ID');
            $table->unsignedInteger('payment_channel_id')->index()->default(0)->comment('支付渠道ID');
            $table->string('channel', 100)->index()->comment('支付渠道');
            $table->unsignedTinyInteger('payment_channel_pay_way_id')->index()->default(0)->comment('支付方式ID');
            $table->string('pay_way', 100)->index()->comment('支付方式');
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
            $table->string('status')->index()->default('pending')->comment('状态 pending 等待付款 processing 付款中 success 支付成功 closed 已关闭 caneled 已取消');
            $table->timestamps();
            $table->unique(['trade_no', 'out_trade_no', 'payment_channel_id','payment_channel_pay_way_id',  'status'], 'uniqid_order');
        });
        DB::statement("ALTER TABLE `payment_orders` comment '支付订单'");
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
