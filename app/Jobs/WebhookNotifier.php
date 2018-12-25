<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Ry\Model\Payway\Webhook;
use App\Events\InternalWebhook;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Ry\HTTPClient\Client;

class WebhookNotifier implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $webhook;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Webhook $webhook)
    {
        $this->webhook = $webhook;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $params = json_decode($this->webhook->context, true);
            $response = (new Client)->request('POST', $this->webhook->url, [
                'form_params' => $params
            ]);
            $context = (string) $response->getBody();
            Event::fire(new InternalWebhook($this->webhook->webhookable, $params, $context));
            if ($context == 'success') {
                DB::transaction(function () use ($context) {
                    $this->webhook->update([
                        'status' => Webhook::STATUS_SUCCESS
                    ]);
                });
            }
        } catch (\Exception $e) {
            Log::warning(sprintf('Webhook通知内部系统失败 file:%s, message:%s', $e->getFile(), $e->getMessage()));
            DB::transaction(function () {
                $this->webhook->update([
                    'status' => Webhook::STATUS_FAIL
                ]);
            });
        }
    }
}
