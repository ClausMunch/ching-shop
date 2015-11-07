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
            $string .= mb_convert_encoding("&#{$chr};", 'UTF-8', 'HTML-ENTITIES');
        }
        return $string;
    }

    /**
     * @return string
     */
    public function anyEmail(): string
    {
        return $this->faker()->email;
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
