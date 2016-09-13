<?php

namespace ChingShop\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

/**
 * Class Test.
 */
class Test extends Command
{
    /** @var array */
    private static $testCommands = [
        'phpcs --standard=./tests/analysis/phpcs.xml app',
        'phpmd --strict app text ./tests/analysis/phpmd.xml',
        'gulp scss-lint',
        'gulp ts-lint',
        './artisan route:cache; ./artisan config:cache; ./artisan optimize',
        'phpunit --testsuite unit --repeat 3',
        'gulp test-database',
        'phpunit --coverage-html build --coverage-clover build/clover.xml',
    ];

    /** @var Process */
    private $process;

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

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $start = Carbon::now();

        $count = count(self::$testCommands);
        $this->line("Running {$count} test suites and analyses");

        foreach (self::$testCommands as $command) {
            $this->line("Starting:\t`{$command}`...");
            $process = $this->makeProcess($command);
            $process->enableOutput();
            $process->setTty(true);
            $process->mustRun($this->outPutter());
            $this->info("✔\tOK:\t`{$command}`");
        }

        $spent = $start->diffForHumans(Carbon::now(), true);

        $this->info(
            "\n✔\t{$count} test suites and analyses passed in ~{$spent}"
        );
    }

    /**
     * @param Process $process
     */
    public function setProcess(Process $process)
    {
        $this->process = $process;
    }

    /**
     * @param string $command
     *
     * @return Process
     */
    private function makeProcess(string $command)
    {
        if (isset($this->process)) {
            $this->process->setCommandLine($command);

            return $this->process;
        }

        return new Process($command);
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
