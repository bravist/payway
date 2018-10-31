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
use Ry\HTTPClient\Client;
use DB;

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
            logger($params);
            logger($this->webhook->url);
            $response = (new Client)->request('POST', $this->webhook->url, [
                'form_params' => $params
            ]);
            $context = (string) $response->getBody();
            logger($context);
            Event::fire(new InternalWebhook($this->webhook->webhookable, $params, $context));
            if ($context == 'success') {
                DB::transaction(function () use ($context) {
                    $this->webhook->update([
                        'status' => Webhook::STATUS_SUCCESS
                    ]);
                });
            }
        } catch (\Exception $e) {
            DB::transaction(function () {
                $this->webhook->update([
                    'status' => Webhook::STATUS_FAIL
                ]);
            });
        }
    }
}
