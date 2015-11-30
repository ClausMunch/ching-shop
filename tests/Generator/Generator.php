<?php

namespace Testing\Generator;

interface Generator
{
    const SANE_ITERATION_LIMIT = 16;

    const UTF8_MAX = 0xFFFF;

    /**
     * @return void
     */
    public function reset();

    /**
     * @return bool
     */
    public function anyBoolean(): bool;

    /**
     * @return string
     */
    public function anyString(): string;

    /**
     * @param string $unwanted
     * @return string
     */
    public function anyStringOtherThan(string $unwanted): string;

    /**
     * @return int
     */
    public function anyInteger(): int;

    /**
     * @return string
     */
    public function anyEmail(): string;

    /**
     * @param array $options
     * @return mixed
     */
    public function anyOneOf(array $options);
}
