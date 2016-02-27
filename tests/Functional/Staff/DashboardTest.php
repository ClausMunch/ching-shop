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
            ->seePageIs(route('auth::login'));
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
}
