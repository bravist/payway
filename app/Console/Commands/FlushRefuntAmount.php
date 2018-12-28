<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Ry\Model\Payway\Order;
use Ry\Model\Payway\Refund;

class FlushRefuntAmount extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:flush-refund-amount';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Flush refund amount';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Order::with('refunds')
            ->whereHas('refunds', function ($query) {
                $query->where('status', Refund::STATUS_SUCCESS);
            })
            ->get()
            ->each(function ($order) {
                $order->refund_amount = $order->refunds()->first()->amount;
                $order->save();
            });

    }
}
