<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Ry\Model\Payway\Webhook;
use App\Jobs\WebhookNotifier;

class CheckFailedWebhookNotifier extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:check-failed-webhook-notifier';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Timely check failed webhook and request';

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
        Webhook::where('status', Webhook::STATUS_FAIL)
                ->get()
                ->each(function ($webhook) {
                    WebhookNotifier::dispatch($webhook)->onQueue('webhook-notifier');
                });
    }
}
