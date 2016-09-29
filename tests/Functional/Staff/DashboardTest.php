<?php

namespace Testing\Functional\Staff;

use Testing\Functional\FunctionalTest;

class DashboardTest extends FunctionalTest
{
    use StaffUser;

    /**
     * Should not be able to access dashboard pages without auth.
     */
    public function testAuthRequired()
    {
        $this->visit(route('staff.dashboard'))
            ->seePageIs(route('login'));
    }

    /**
     * Should be able to hit the index page.
     */
    public function testIndex()
    {
        $this->actingAs($this->staffUser())
            ->visit(route('staff.dashboard'))
            ->seePageIs(route('staff.dashboard'));
    }

    /**
     * CSRF token should be available on staff pages.
     */
    public function testCanGetCsrfToken()
    {
        $this->actingAs($this->staffUser())->visit(route('staff.dashboard'));
        $this->see('csrf-token');
        $this->assertNotEmpty($this->documentCsrfToken());
    }
}
