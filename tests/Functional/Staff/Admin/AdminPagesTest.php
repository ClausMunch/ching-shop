<?php

namespace Testing\Functional\Staff\Admin;

use Testing\Functional\FunctionalTest;
use Testing\Functional\Staff\StaffUser;

class AdminPagesTest extends FunctionalTest
{
    use StaffUser;

    /**
     * Should be able to view PHP info page.
     */
    public function testCanViewPhpInfo()
    {
        $this->actingAs($this->staffUser())
            ->visit('/staff/php-info')
            ->assertResponseOk()
            ->see('PHP Version');
    }

    /**
     * Non-staff users should not be able to view the PHP info page.
     */
    public function testNonStaffCantViewPhpInfo()
    {
        $this->visit('/staff/php-info')->seePageIs(route('auth::login'));
    }
}
