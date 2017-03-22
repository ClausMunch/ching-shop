<?php

namespace Testing\Functional;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Testing\BrowserKitTestCase;
use Testing\Generator\GeneratesValues;

/**
 * Class FunctionalTest.
 */
abstract class FunctionalTest extends BrowserKitTestCase
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
        self::assertEquals('sync', env('QUEUE_DRIVER'));
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
    public function getElement(string $selector)
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
    public function getElementText(string $selector): string
    {
        return trim($this->getElement($selector)->text());
    }

    /**
     * @param string $selector
     * @param string $attribute
     *
     * @return string
     */
    public function getElementAttribute(string $selector, string $attribute)
    {
        return trim($this->getElement($selector)->attr($attribute));
    }

    /**
     * @param string $search
     *
     * @return int
     */
    public function countOnPage(string $search): int
    {
        return mb_substr_count($this->crawler()->html(), $search);
    }
}
