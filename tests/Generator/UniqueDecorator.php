<?php

namespace Testing\Generator;

/**
 * Class UniqueDecorator
 * Ensures unique values from a test data generator.
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
     * Reset used values.
     */
    public function reset()
    {
        $this->usedValues = [];
        $this->generator->reset();
    }

    /**
     * @throws GenerationException
     *
     * @return bool
     */
    public function anyBoolean(): bool
    {
        return $this->ensureUnusedValue(function () {
            return $this->generator->anyBoolean();
        });
    }

    /**
     * @throws GenerationException
     *
     * @return string
     */
    public function anyString(): string
    {
        return $this->ensureUnusedValue(function () {
            return $this->generator->anyString();
        });
    }

    /**
     * @param string $unwanted
     *
     * @throws GenerationException
     *
     * @return string
     */
    public function anyStringOtherThan(string $unwanted): string
    {
        return $this->ensureUnusedValue(function () use ($unwanted) {
            return $this->generator->anyStringOtherThan($unwanted);
        });
    }

    /**
     * @return int
     */
    public function anyInteger(): int
    {
        return $this->ensureUnusedValue(function () {
            return $this->generator->anyInteger();
        });
    }

    /**
     * @throws GenerationException
     *
     * @return string
     */
    public function anyEmail(): string
    {
        return $this->ensureUnusedValue(function () {
            return $this->generator->anyEmail();
        });
    }

    /**
     * @throws GenerationException
     *
     * @return string
     */
    public function anySlug(): string
    {
        return $this->ensureUnusedValue(function () {
            return $this->generator->anySlug();
        });
    }

    /**
     * @param array $options
     *
     * @return mixed
     */
    public function anyOneOf(array $options)
    {
        return $this->ensureUnusedValue(function () use ($options) {
            return $this->generator->anyOneOf($options);
        });
    }

    /**
     * @param callable $generate
     *
     * @throws GenerationException
     *
     * @return mixed
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
