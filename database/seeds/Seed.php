<?php

use Faker\Generator;
use Illuminate\Database\Seeder;

abstract class Seed extends Seeder
{
    /** @var Generator */
    private $faker;

    /**
     * @param Generator $faker
     */
    public function __construct(Generator $faker)
    {
        $this->faker = $faker;
    }

    /**
     * @return Generator
     */
    protected function faker()
    {
        return $this->faker;
    }

    /**
     * @param callable $action
     * @param int $times
     *
     * @return array
     */
    protected function repeat(callable $action, int $times = 1): array
    {
        $results = [];
        for ($i = 0; $i < $times; $i++) {
            $results[] = $action();
        }

        return $results;
    }
}
