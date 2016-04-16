<?php

namespace Testing\Generator;

class FakerGenerator implements Generator
{
    /** @var \Faker\Generator */
    private $faker;

    /**
     * (No action required to reset faker generator).
     */
    public function reset()
    {
        mt_srand();
    }

    /**
     * @return bool
     */
    public function anyBoolean(): bool
    {
        return $this->faker()->boolean();
    }

    /**
     * @param int $length
     *
     * @return string
     */
    public function anyString(int $length = 0): string
    {
        $length = $length ?: mt_rand(12, Generator::SANE_ITERATION_LIMIT);

        return $this->faker()->text($length);
    }

    /**
     * @param string $unwanted
     *
     * @return string
     */
    public function anyStringOtherThan(string $unwanted): string
    {
        return strrev($unwanted).$this->faker()->shuffleString($unwanted);
    }

    /**
     * @return int
     */
    public function anyInteger(): int
    {
        return $this->faker()->randomNumber();
    }

    /**
     * @return string
     */
    public function anyEmail(): string
    {
        return $this->faker()->email;
    }

    /**
     * @return string
     */
    public function anySlug(): string
    {
        return $this->faker()->slug;
    }

    /**
     * @param array $options
     *
     * @return mixed
     */
    public function anyOneOf(array $options)
    {
        return $this->faker()->randomElement($options);
    }

    /**
     * @return \Faker\Generator
     */
    private function faker(): \Faker\Generator
    {
        if (!isset($this->faker)) {
            $this->faker = \Faker\Factory::create();
        }

        return $this->faker;
    }
}
