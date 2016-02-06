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
}
