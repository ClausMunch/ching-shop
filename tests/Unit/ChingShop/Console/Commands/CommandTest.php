<?php

namespace Testing\Unit\ChingShop\Console\Commands;

use Illuminate\Container\Container;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;
use Testing\Unit\UnitTest;

abstract class CommandTest extends UnitTest
{
    /** @var Container */
    protected $container;

    /**
     * Set up testing container for each test.
     */
    public function setUp()
    {
        parent::setUp();

        $this->container = new Container();
        Container::setInstance($this->container);
    }

    /**
     * @param Command $command
     *
     * @return CommandTester
     */
    protected function commandTester(Command $command): CommandTester
    {
        return new CommandTester($command);
    }
}
