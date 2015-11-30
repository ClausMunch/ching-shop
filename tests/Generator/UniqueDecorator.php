<?php

namespace Testing\Generator;

/**
 * Class UniqueDecorator
 * Ensures unique values from a test data generator
 * @package Testing\Generator
 */
class UniqueDecorator implements Generator
{
    /** @var Generator */
    private $generator;

    /** @var array */
    private $usedValues = [];

    /** @var int */
    private $iterationLimit = 100;

    /**
     * @param Generator $generator
     */
    public function __construct(Generator $generator)
    {
        $this->generator = $generator;
    }

    /**
     * Reset used values
     */
    public function reset()
    {
        $this->usedValues = [];
        $this->generator->reset();
    }

    /**
     * @return bool
     * @throws GenerationException
     */
    public function anyBoolean(): bool
    {
        return $this->ensureUnusedValue(function() {
            return $this->generator->anyBoolean();
        });
    }

    /**
     * @return string
     * @throws GenerationException
     */
    public function anyString(): string
    {
        return $this->ensureUnusedValue(function() {
            return $this->generator->anyString();
        });
    }

    /**
     * @param string $unwanted
     * @return string
     * @throws GenerationException
     */
    public function anyStringOtherThan(string $unwanted): string
    {
        return $this->ensureUnusedValue(function() use ($unwanted) {
            return $this->generator->anyStringOtherThan($unwanted);
        });
    }

    /**
     * @return int
     */
    public function anyInteger(): int
    {
        return $this->ensureUnusedValue(function() {
            return $this->generator->anyInteger();
        });
    }

    /**
     * @return string
     * @throws GenerationException
     */
    public function anyEmail(): string
    {
        return $this->ensureUnusedValue(function() {
            return $this->generator->anyEmail();
        });
    }

    /**
     * @param array $options
     * @return mixed
     */
    public function anyOneOf(array $options)
    {
        return $this->ensureUnusedValue(function() use ($options) {
            return $this->generator->anyOneOf($options);
        });
    }

    /**
     * @param callable $generate
     * @return mixed
     * @throws GenerationException
     */
    private function ensureUnusedValue(callable $generate): mixed
    {
        for ($i = 0; $i < $this->iterationLimit; $i++) {
            $value = $generate();
            if (!in_array($value, $this->usedValues, true)) {
                return $value;
            }
        }
        throw new GenerationException(sprintf(
            'Failed to generate unique value within iteration limit of %s',
            $this->iterationLimit
        ));
    }
}
