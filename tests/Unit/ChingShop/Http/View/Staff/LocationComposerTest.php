<?php

namespace Testing\Unit\ChingShop\Http\View\Staff;

use ChingShop\Http\View\Staff\HttpCrudInterface;
use ChingShop\Http\View\Staff\StaffLocationComposer;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Contracts\View\View;
use Illuminate\Routing\Router;
use Mockery\MockInterface;
use PHPUnit_Framework_MockObject_MockObject as MockObject;
use Testing\Unit\UnitTest;

class LocationComposerTest extends UnitTest
{
    /** @var StaffLocationComposer */
    private $locationComposer;

    /** @var Router|MockInterface */
    private $router;

    /** @var UrlGenerator|MockInterface */
    private $urlGenerator;

    /**
     * Initialise location composer with mock dependencies.
     */
    public function setUp()
    {
        parent::setUp();

        $this->router = $this->mockery(Router::class);
        $this->urlGenerator = $this->mockery(UrlGenerator::class);

        $this->locationComposer = new StaffLocationComposer(
            $this->router,
            $this->urlGenerator
        );
    }

    /**
     * Sanity check can instantiate.
     */
    public function testConstruct()
    {
        $this->assertInstanceOf(
            StaffLocationComposer::class,
            $this->locationComposer
        );
    }

    /**
     * Should bind the composer into the view.
     */
    public function testCompose()
    {
        /** @var View|MockObject $view */
        $view = $this->makeMock(View::class);

        $view->expects($this->once())
            ->method('with')
            ->with([
                'location' => $this->locationComposer,
            ]);

        $this->locationComposer->compose($view);
    }

    /**
     * Should get the location name from the router.
     */
    public function testName()
    {
        $currentRouteName = $this->generator()->anyString();
        $this->router->shouldReceive('currentRouteName')
            ->andReturn($currentRouteName);

        $this->assertSame($currentRouteName, $this->locationComposer->name());
    }

    /**
     * Should return true if name is exact match.
     */
    public function testIs()
    {
        $currentRouteName = $this->generator()->anyString();
        $this->router->shouldReceive('currentRouteName')
            ->andReturn($currentRouteName);

        $this->assertTrue($this->locationComposer->is($currentRouteName));
    }

    /**
     * Should return true if name is included in current route name.
     */
    public function testIsIn()
    {
        $routeParts = [
            $this->anyStringWithoutDots(),
            $this->anyStringWithoutDots(),
        ];

        $this->router->shouldReceive('currentRouteName')
            ->andReturn(implode('.', $routeParts));

        $this->assertTrue($this->locationComposer->isIn($routeParts[0]));
    }

    /**
     * Should give 'active' if the given location
     * is in the current location.
     */
    public function testPutActive()
    {
        $routeParts = [
            $this->anyStringWithoutDots(),
            $this->anyStringWithoutDots(),
        ];

        $this->router->shouldReceive('currentRouteName')
            ->andReturn(implode('.', $routeParts));

        $this->assertSame(
            'active',
            $this->locationComposer->putActive($routeParts[0])
        );
    }

    /**
     * Should give the given string
     * if given location is in the current location.
     */
    public function testPutIfIs()
    {
        $currentRouteName = $this->generator()->anyString();
        $this->router->shouldReceive('currentRouteName')
            ->andReturn($currentRouteName);

        $givenString = $this->anyStringWithoutDots();

        $this->assertSame(
            $givenString,
            $this->locationComposer->putIfIs($givenString, $currentRouteName)
        );

        $notCurrentRoute = $this->generator()->anyStringOtherThan(
            $currentRouteName
        );
        $this->assertSame(
            '',
            $this->locationComposer->putIfIs($givenString, $notCurrentRoute)
        );
    }

    /**
     * Should use the URL generate to generate a Show action HREF
     * for a crud resource.
     */
    public function testShowHrefFor()
    {
        $crud = $this->makeMockCrudResource();
        $routePath = $this->anyStringWithoutDots();
        $crud->shouldReceive('routePath')->andReturn($routePath);

        $crudID = $this->generator()->anyInteger();
        $crud->shouldReceive('crudId')->once()->andReturn($crudID);

        $URL = $this->generator()->anyString();
        $this->urlGenerator->shouldReceive('route')
            ->with("{$routePath}.show", $crudID)
            ->andReturn($URL);

        $this->assertSame(
            $URL,
            $this->locationComposer->showHrefFor($crud)
        );
    }

    /**
     * Should give 'PUT' for an existing crud resource
     * and 'POST' for a new one.
     */
    public function testPersistMethodFor()
    {
        $newCrud = $this->makeMockCrudResource();
        $newCrud->shouldReceive('isStored')->andReturn(false);

        $this->assertSame(
            'POST',
            $this->locationComposer->persistMethodFor($newCrud)
        );

        $oldCrud = $this->makeMockCrudResource();
        $oldCrud->shouldReceive('isStored')->andReturn(true);

        $this->assertSame(
            'PUT',
            $this->locationComposer->persistMethodFor($oldCrud)
        );
    }

    /**
     * Should use the store route with no ID for a new resource.
     */
    public function testPersistActionForNewResource()
    {
        $newCrud = $this->makeMockCrudResource();
        $newCrud->shouldReceive('isStored')->andReturn(false);
        $routePath = $this->generator()->anyString();
        $newCrud->shouldReceive('routePath')->andReturn($routePath);

        $URL = $this->generator()->anyString();
        $this->urlGenerator->shouldReceive('route')
            ->with("{$routePath}.store")
            ->andReturn($URL);

        $this->assertSame(
            $URL,
            $this->locationComposer->persistActionFor($newCrud)
        );
    }

    /**
     * Should use the update route with an ID for an existing resource.
     */
    public function testPersistActionForExistingResource()
    {
        $existingCrud = $this->makeMockCrudResource();
        $existingCrud->shouldReceive('isStored')->andReturn(true);
        $routePath = $this->generator()->anyString();
        $existingCrud->shouldReceive('routePath')
            ->andReturn($routePath);
        $crudID = $this->generator()->anyInteger();
        $existingCrud->shouldReceive('crudId')->andReturn($crudID);

        $URL = $this->generator()->anyString();
        $this->urlGenerator->shouldReceive('route')
            ->with("{$routePath}.update", $crudID)
            ->andReturn($URL);

        $this->assertSame(
            $URL,
            $this->locationComposer->persistActionFor($existingCrud)
        );
    }

    /**
     * @return HttpCrudInterface|MockInterface
     */
    private function makeMockCrudResource(): MockInterface
    {
        return $this->mockery(HttpCrudInterface::class);
    }

    /**
     * @return string
     */
    private function anyStringWithoutDots(): string
    {
        return str_replace('.', '-', $this->generator()->anyString());
    }
}
