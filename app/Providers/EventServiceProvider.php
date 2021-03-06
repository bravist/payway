<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\Event' => [
            'App\Listeners\EventListener',
        ],
        //创建订单请求日志（业务系统请求网关）监听器
        'App\Events\InternalRequestOrder' => [
            'App\Listeners\SaveInternalRequestOrderLog',
        ],
        'App\Events\ExternalRequestOrder' => [
            'App\Listeners\SaveExternalRequestOrderLog'
        ],
        'App\Events\ExternalQueryOrder' => [
            'App\Listeners\SaveExternalQueryPaidOrderLog',
            'App\Listeners\SaveExternalQueryExpiredOrderLog',
            'App\Listeners\SaveExternalQueryOrderLog'
        ],
        'App\Events\ExternalWebhook' => [
            'App\Listeners\SaveExternalWebhookLog'
        ],
        'App\Events\InternalWebhook' => [
            'App\Listeners\SaveInternalWebhookLog'
        ],
        'App\Events\InternalRequestRefund' => [
            'App\Listeners\SaveInternalRequestRefundLog',
        ],
        'App\Events\ExternalRequestRefund' => [
            'App\Listeners\SaveExternalRequestRefundLog'
        ],
        'App\Events\ExternalQueryRefund' => [
            'App\Listeners\SaveExternalQueryRefundLog'
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
