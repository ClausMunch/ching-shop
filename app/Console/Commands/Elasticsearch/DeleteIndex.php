<?php

namespace ChingShop\Console\Commands\Elasticsearch;

use Elasticsearch\Client;
use Illuminate\Console\Command;

/**
 * Delete the ElasticSearch index.
 */
class DeleteIndex extends Command
{
    use ElasticsearchConfig;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'elasticsearch:index:delete {--y|yes}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete the Elasticsearch index.';

    /** @var Client */
    private $elasticsearch;

    /**
     * @param Client $elasticsearch
     */
    public function __construct(Client $elasticsearch)
    {
        parent::__construct();

        $this->elasticsearch = $elasticsearch;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (!$this->option('yes') && !$this->shouldContinue()) {
            $this->line('Cancelled.');

            return;
        }

        if (!$this->elasticsearch->indices()->exists(
            ['index' => $this->indexName()]
        )
        ) {
            $this->warn(
                "There is no index called `{$this->indexName()}`, stopping."
            );

            return;
        }

        $this->line("Deleting the `{$this->indexName()}` index...");
        try {
            $this->elasticsearch->indices()->delete(
                ['index' => $this->indexName()]
            );
            $this->info("Deleted the `{$this->indexName()}` index.");
        } catch (\Throwable $err) {
            $this->error(
                "Failed to delete index `{$this->indexName()}`: "
                .$err->getMessage()
            );
        }
    }

    /**
     * @return bool
     */
    private function shouldContinue():bool
    {
        return $this->confirm(
            "Delete the `{$this->indexName()}` index in Elasticsearch?"
        );
    }
}
