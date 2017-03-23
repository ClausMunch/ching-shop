<?php

namespace ChingShop\Modules\Sales\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Log;

/**
 * Physically print the shipping address label for an order.
 */
class PrintOrderAddress implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    const QUEUE_CONNECTION = 'print-jobs';
    const QUEUE_NAME = 'ching-shop-print-jobs';

    /** @var array */
    private $addressParts;

    /**
     * Create a new job instance.
     *
     * @param array $addressParts To be serialized.
     */
    public function __construct(array $addressParts)
    {
        /** @noinspection UnusedConstructorDependenciesInspection */
        $this->addressParts = $addressParts;
    }

    /**
     * These jobs are picked up by the printer and so should not be picked up by
     * Laravel.
     */
    public function handle()
    {
        Log::error(
            sprintf(
                'Job to print address %s has been picked up by Laravel.',
                $this->addressParts
            )
        );
    }
}
