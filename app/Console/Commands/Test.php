<?php

namespace ChingShop\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\ProcessBuilder;

/**
 * Class Test
 *
 * @package ChingShop\Console\Commands
 *
 * Run all tests and static analyses.
 */
class Test extends Command
{
    /** @var array */
    private $testCommands = [
        'phpcs --standard=./tests/analysis/phpcs.xml app',
        'phpmd --strict app text ./tests/analysis/phpmd.xml',
        'phpunit --testsuite unit --repeat 3',
        'phpunit --testsuite unit --coverage-html build',
        'gulp generate-test-db',
        'phpunit --testsuite functional',
    ];

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run the full test suite.';

    /** @var ProcessBuilder */
    private $processBuilder;

    /**
     * Create a new command instance.
     *
     * @param ProcessBuilder $processBuilder
     */
    public function __construct(ProcessBuilder $processBuilder)
    {
        parent::__construct();

        $this->processBuilder = $processBuilder;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $count = count($this->testCommands);
        $this->line("Running {$count} test suites and analyses");

        foreach ($this->testCommands as $command) {
            $this->line("Starting:\t`{$command}`...");
            $process = new Process($command);
            $process->setWorkingDirectory(base_path());
            $process->enableOutput();
            $process->setTty(true);
            $process->mustRun($this->outPutter());
            $this->info("✔\tOK:\t`{$command}`");
        };

        $this->info("\n✔\t{$count} test suites and analyses passed");
    }

    /**
     * @return \Closure
     */
    private function outPutter()
    {
        return function ($type, $output) {
            if ($type === Process::ERR) {
                $this->error($output);
            } else {
                echo $output;
            }
        };
    }
}
