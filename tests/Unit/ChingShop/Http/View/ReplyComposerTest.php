<?php

namespace Testing\Unit\ChingShop\Http\View;

use ChingShop\Http\View\ReplyComposer;
use Illuminate\Contracts\View\View;
use Illuminate\Session\Store as SessionStore;
use Illuminate\Support\MessageBag;
use Mockery;
use Mockery\MockInterface;
use PHPUnit_Framework_MockObject_MockObject as MockObject;
use Testing\Unit\UnitTest;

class ReplyComposerTest extends UnitTest
{
    /** @var ReplyComposer */
    private $replyComposer;

    /** @var SessionStore|MockInterface */
    private $sessionStore;

    /** @var MessageBag|MockInterface */
    private $errors;

    /**
     * Initialise reply composer with mock session store.
     */
    public function setUp()
    {
        parent::setUp();

        $this->sessionStore = $this->mockery(SessionStore::class);

        $this->errors = $this->mockery(MessageBag::class);
        $this->sessionStore->shouldReceive('get')
            ->with('errors', Mockery::type(MessageBag::class))
            ->andReturn($this->errors);

        $this->replyComposer = new ReplyComposer($this->sessionStore);
    }

    /**
     * Sanity check instantiation.
     */
    public function testConstruct()
    {
        $this->assertInstanceOf(ReplyComposer::class, $this->replyComposer);
    }

    /**
     * Should bind the composer into the view.
     */
    public function testCompose()
    {
        /** @var View|MockObject $view */
        $view = $this->makeMock(View::class);
        $view->expects($this->atLeastOnce())
            ->method('with')
            ->with([
                'reply' => $this->replyComposer,
            ]);
        $this->replyComposer->compose($view);
    }

    /**
     * Should give 'has-error' if the given field-name has errors.
     */
    public function testPutHasError()
    {
        $fieldName = $this->generator()->anyString();

        $this->errors->shouldReceive('has')
            ->with($fieldName)
            ->andReturn(true);

        $this->assertSame(
            'has-error',
            $this->replyComposer->putHasError($fieldName)
        );
    }

    /**
     * Should use the error message bag to give errors for a field.
     */
    public function testErrorsFor()
    {
        $errors = [
            $this->generator()->anyString(),
            $this->generator()->anyString(),
        ];
        $fieldName = $this->generator()->anyString();

        $this->errors->shouldReceive('get')
            ->with($fieldName)
            ->andReturn($errors);

        $this->assertSame(
            $errors,
            $this->replyComposer->errorsFor($fieldName)
        );
    }

    /**
     * Should give old input when present.
     */
    public function testOldInputOrWithOldInput()
    {
        $oldInputValue = $this->generator()->anyString();
        $fieldName = $this->generator()->anyString();

        $this->sessionStore->shouldReceive('getOldInput')
            ->andReturn([
                $fieldName => [$oldInputValue],
            ]);

        $this->assertSame(
            $oldInputValue,
            $this->replyComposer->oldInputOr(
                $fieldName,
                $this->generator()->anyString()
            )
        );
    }

    /**
     * Should give the fallback string when old input is not present.
     */
    public function testOldInputOrWithoutOldInput()
    {
        $fieldName = $this->generator()->anyString();

        $this->sessionStore->shouldReceive('getOldInput')
            ->andReturn([$fieldName => []]);

        $fallbackValue = $this->generator()->anyString();

        $this->assertSame(
            $fallbackValue,
            $this->replyComposer->oldInputOr(
                $fieldName,
                $fallbackValue
            )
        );
    }
}
