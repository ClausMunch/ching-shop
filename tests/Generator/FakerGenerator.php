<?php

namespace Testing\Generator;

class FakerGenerator implements Generator
{
    /** @var \Faker\Generator */
    private $faker;

    /**
     * (No action required to reset faker generator)
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
     * @return string
     */
    public function anyString(): string
    {
        $string = '';
        $length = mt_rand(1, Generator::SANE_ITERATION_LIMIT);
        for ($i = 0; $i < $length; $i++) {
            $chr = mt_rand(0x0000, Generator::UTF8_MAX);
            $string .= mb_convert_encoding(
                "&#{$chr};",
                'UTF-8',
                'HTML-ENTITIES'
            );
        }
        return $string;
    }

    /**
     * @param string $unwanted
     * @return string
     */
    public function anyStringOtherThan(string $unwanted): string
    {
        return strrev($unwanted) . $this->faker()->shuffleString($unwanted);
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
     * @param array $options
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
