<?php

namespace Testing\Unit\ChingShop\Console\Commands;

use ChingShop\Console\Commands\Test as TestCommand;
use PHPUnit_Framework_MockObject_MockObject as MockObject;
use Symfony\Component\Process\Process;

/**
 * Class TestTest
 *
 * @package Testing\Unit\ChingShop\Console\Commands
 */
class TestTest extends CommandTest
{
    /** @var TestCommand */
    private $testCommand;

    /** @var Process|MockObject */
    private $process;

    /**
     * Set up test command for each test.
     */
    public function setUp()
    {
        parent::setUp();

        $this->testCommand = new TestCommand;
        $this->testCommand->setLaravel($this->container);

        $this->process = $this->makeMock(Process::class);
        $this->testCommand->setProcess($this->process);
    }

    /**
     * Sanity check for instantiation.
     */
    public function testConstruct()
    {
        $this->assertInstanceOf(TestCommand::class, $this->testCommand);
    }

    /**
     * Should execute some test commands.
     */
    public function testRunsTests()
    {
        $this->process->expects($this->atLeastOnce())
            ->method('setCommandLine')
            ->with($this->isType('string'));

        $this->process->expects($this->atLeastOnce())
            ->method('mustRun');

        $this->tester($this->testCommand)->execute([]);
    }
}
