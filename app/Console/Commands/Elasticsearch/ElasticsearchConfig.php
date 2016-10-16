<?php

namespace ChingShop\Console\Commands\Elasticsearch;

/**
 * Access Elasticsearch configuration.
 */
trait ElasticsearchConfig
{
    /**
     * @return string
     */
    private function indexName(): string
    {
        return config('scout.elasticsearch.index');
    }
}
