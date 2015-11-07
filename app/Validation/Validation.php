<?php

namespace ChingShop\Validation;

interface Validation
{
    /**
     * @param array $testData
     * @param array $rules
     * @return bool
     */
    public function passes(array $testData, array $rules): bool;

    /**
     * @return array
     */
    public function messages(): array;
}
