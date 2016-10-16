<?php

namespace ChingShop\Console\Commands\Elasticsearch;

use ChingShop\Modules\Catalogue\Domain\Product\Product;
use Illuminate\Console\Command;

/**
 * Delete and re-import
 */
class RefreshIndex extends Command
{
    use ElasticsearchConfig;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'elasticsearch:index:refresh {--y|yes}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete and re-import the Elasticsearch index.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (!$this->option('yes') && !$this->shouldContinue()) {
            $this->line('Cancelled.');

            return;
        }

        $this->line("Refreshing the `{$this->indexName()}` index...");
        try {
            $this->call('elasticsearch:index:delete', ['-y' => true]);
            $this->call('scout:import', ['model' => Product::class]);
            $this->info("Refreshed the `{$this->indexName()}` index.");
        } catch (\Throwable $err) {
            $this->error(
                "Failed to refresh index `{$this->indexName()}`: "
                . $err->getMessage()
            );
        }
    }

    /**
     * @return bool
     */
    private function shouldContinue():bool
    {
        return $this->confirm(
            "Delete and re-import the `{$this->indexName()}` index"
            . ' in Elasticsearch?'
        );
    }
}
