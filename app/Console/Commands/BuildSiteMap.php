<?php

namespace ChingShop\Console\Commands;

use ChingShop\Modules\Catalogue\Domain\SiteMap;
use Illuminate\Console\Command;
use Illuminate\Contracts\Filesystem\Factory;

/**
 * Class BuildSiteMap.
 */
class BuildSiteMap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sitemap:build';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate the XML sitemap.';

    /** @var SiteMap */
    private $siteMap;

    /** @var Factory */
    private $fileSystem;

    /**
     * BuildSiteMap constructor.
     *
     * @param SiteMap $siteMap
     * @param Factory $fileSystem
     */
    public function __construct(SiteMap $siteMap, Factory $fileSystem)
    {
        parent::__construct();

        $this->siteMap = $siteMap;
        $this->fileSystem = $fileSystem;
    }

    /**
     * Execute the console command.
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function handle()
    {
        $this->line('Writing sitemap.xml to public disk...');
        $siteMap = (string) $this->siteMap;
        $this->fileSystem->disk('public')->put('sitemap.xml', $siteMap);
        $this->info(
            sprintf(
                'Wrote %dkb to sitemap.xml in public disk.',
                strlen($siteMap) / 1024
            )
        );
    }
}
