<?php

namespace Testing\Functional;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Testing\Generator\GeneratesValues;
use Testing\TestCase;

/**
 * Class FunctionalTest
 * @package Testing\Functional
 *
 * General behaviours for functional tests.
 */
abstract class FunctionalTest extends TestCase
{
    use GeneratesValues, DatabaseTransactions;

    /**
     * Confirm test database is being used.
     */
    public function setUp()
    {
        parent::setUp();

        self::assertEquals(
            'testing',
            $this->app->make('db')->connection()->getName()
        );
    }

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
     *
     * @return \Symfony\Component\DomCrawler\Crawler
     */
    protected function getElement(string $selector)
    {
        return $this->crawler()
            ->filter($selector)
            ->first();
    }

    /**
     * @param string $selector
     *
     * @return string
     */
    protected function getElementText(string $selector): string
    {
        return trim($this->getElement($selector)->text());
    }

    /**
     * @param string $selector
     * @param string $attribute
     *
     * @return string
     */
    protected function getElementAttribute(string $selector, string $attribute)
    {
        return trim($this->getElement($selector)->attr($attribute));
    }
}
