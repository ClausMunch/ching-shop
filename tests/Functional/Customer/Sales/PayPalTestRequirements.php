<?php

namespace Testing\Functional\Customer\Sales;

use Testing\Functional\FunctionalTest;

/**
 * Helper for skipping tests if requirements for functional PayPal testing are
 * not fulfilled.
 */
trait PayPalTestRequirements
{
    /** @const string[] */
    private static $requiredConfig = [
        'acct1.ClientId',
        'acct1.ClientSecret',
        'test-buyer.email',
        'test-buyer.password',
    ];

    /** @var bool */
    private static $remoteOk;

    /**
     * @param FunctionalTest $test
     *
     * @throws \PHPUnit_Framework_SkippedTestError
     *
     * @return bool
     */
    private function checkPayPalTestRequirements(FunctionalTest $test): bool
    {
        return $this->configOk($test) && $this->remoteOk($test);
    }

    /**
     * @param FunctionalTest $test
     *
     * @throws \PHPUnit_Framework_SkippedTestError
     *
     * @return bool
     */
    private function configOk(FunctionalTest $test): bool
    {
        foreach (self::$requiredConfig as $configKey) {
            if (!config("payment.paypal.{$configKey}")) {
                $test->markTestSkipped(
                    "Missing required {$configKey} config for PayPal test."
                );

                return false;
            }
        }

        return true;
    }

    /**
     * @param FunctionalTest $test
     *
     * @throws \PHPUnit_Framework_SkippedTestError
     *
     * @return bool
     */
    private function remoteOk(FunctionalTest $test): bool
    {
        $baseUrl = parse_url(config('payment.paypal.base-url'), PHP_URL_HOST);

        if (self::$remoteOk === null) {
            self::$remoteOk = (bool) @fsockopen($baseUrl, 443);
        }

        if (!self::$remoteOk) {
            $test->markTestSkipped(
                "Unable to connect to PayPal at `{$baseUrl}`."
            );
        }

        return self::$remoteOk;
    }
}
