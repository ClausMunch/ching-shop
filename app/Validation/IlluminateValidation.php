<?php

namespace ChingShop\Validation;

use Illuminate\Contracts\Support\MessageBag;
use Illuminate\Contracts\Validation\Factory;
use Illuminate\Validation\Validator;

/**
 * Class IlluminateValidation.
 */
class IlluminateValidation implements ValidationInterface
{
    /** @var Factory */
    private $factory;

    /** @var Validator */
    private $validation;

    /**
     * @param Factory $factory
     */
    public function __construct(Factory $factory)
    {
        $this->factory = $factory;
    }

    /**
     * @param array $testData
     * @param array $rules
     *
     * @return bool
     */
    public function passes(array $testData, array $rules): bool
    {
        $this->validation = $this->factory->make($testData, $rules);

        return $this->validation->passes();
    }

    /**
     * @return array|MessageBag
     */
    public function messages(): array
    {
        if ($this->validation === null) {
            return [];
        }

        return $this->validation->messages();
    }
}
