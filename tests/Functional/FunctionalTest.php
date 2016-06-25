<?php

namespace Testing\Functional;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Testing\Generator\GeneratesValues;
use Testing\TestCase;

abstract class FunctionalTest extends TestCase
{
    use GeneratesValues, DatabaseTransactions;

    /**
     * Close Mockery.
     */
    public function tearDown()
    {
        parent::tearDown();
        $this->generator()->reset();
    }

    /**
     * @return string
     */
    protected function documentCsrfToken(): string
    {
        return (string) $this->crawler()
            ->filter('[name=csrf-token]')
            ->first()
            ->attr('content');
    }

    /**
     * @param string $selector
     * @param string $attribute
     *
     * @return string
     */
    protected function documentQueryAttribute(
        string $selector,
        string $attribute
    ) {
        return (string) $this->crawler()
            ->filter($selector)
            ->first()
            ->attr($attribute);
    }
}
