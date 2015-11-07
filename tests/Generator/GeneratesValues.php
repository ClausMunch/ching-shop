<?php

namespace Testing\Generator;

trait GeneratesValues
{
    /** @var Generator */
    private $generator;

    /**
     * @return Generator
     */
    protected function generator(): Generator
    {
        if (!isset($this->generator)) {
            $this->generator = new FakerGenerator;
        }
        return $this->generator;
    }

    /**
     * Ensure unique values from generator
     */
    protected function useUniqueValues()
    {
        if (!$this->generator() instanceof UniqueDecorator) {
            $this->generator = new UniqueDecorator($this->generator());
        }
    }
}
