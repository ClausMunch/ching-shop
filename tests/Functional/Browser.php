<?php

namespace Testing\Functional;

use Browser\Casper;

/**
 * Use the test browser functionality as a trait.
 */
trait Browser
{
    /** @var Casper */
    private $browser;

    /**
     * @return Casper
     */
    private function browser(): Casper
    {
        if ($this->browser === null) {
            $phantomJsBin = base_path(
                'node_modules/phantomjs-prebuilt/bin/phantomjs'
            );
            $this->assertFileExists($phantomJsBin);
            putenv("PHANTOMJS_EXECUTABLE={$phantomJsBin}");
            $casperJsPath = base_path('node_modules/casperjs/bin/');
            $this->assertFileExists("{$casperJsPath}casperjs");
            $this->browser = new Casper($casperJsPath);
            $this->browser->setDebug(true);
            $this->browser->setOptions(
                [
                    'exitOnError' => 'true',
                ]
            );
        }

        return $this->browser;
    }

    /**
     * Save a screen-shot of the current test browser page.
     *
     * @param string $suffix
     */
    private function browserScreenShot(string $suffix = '')
    {
        $this->browser()->capturePage(
            storage_path(
                'test/browser/'.date(DATE_ISO8601)."-{$suffix}.png"
            )
        );
        $htmlCapture = storage_path(
            'test/browser/'.date(DATE_ISO8601)."-{$suffix}-foobar.html"
        );
        $this->browser()->evaluate(<<<js
this.echo('Saving HTML to {$htmlCapture}');
require('fs').write('{$htmlCapture}', this.getHtml(), 'w');
return 'Saved HTML to {$htmlCapture}';
js
        );
    }
}
