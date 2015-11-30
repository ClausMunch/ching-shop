<?php

namespace Testing\Unit\ChingShop\Http\View;

use Testing\Unit\UnitTest;

use ChingShop\Http\View\ReplyComposer;

use Mockery;
use Mockery\MockInterface;

use Illuminate\Support\MessageBag;
use Illuminate\Contracts\View\View;
use Illuminate\Session\Store as SessionStore;

class ReplyComposerTest extends UnitTest
{
    /** @var ReplyComposer */
    private $replyComposer;

    /** @var SessionStore|MockInterface */
    private $sessionStore;

    /** @var MessageBag|MockInterface */
    private $errors;

    /**
     * Initialise reply composer with mock session store
     */
    public function setUp()
    {
        parent::setUp();

        $this->sessionStore = $this->makeMock(SessionStore::class);

        $this->errors = $this->makeMock(MessageBag::class);
        $this->sessionStore->shouldReceive('get')
            ->with('errors', Mockery::type(MessageBag::class))
            ->andReturn($this->errors);

        $this->replyComposer = new ReplyComposer($this->sessionStore);
    }

    /**
     * Sanity check instantiation
     */
    public function testConstruct()
    {
        $this->assertInstanceOf(ReplyComposer::class, $this->replyComposer);
    }

    /**
     * Should bind the composer into the view
     */
    public function testCompose()
    {
        /** @var View|MockInterface $view */
        $view = $this->makeMock(View::class);

        $view->shouldReceive('with')->with([
            'reply' => $this->replyComposer
        ]);

        $this->replyComposer->compose($view);
    }

    /**
     * Should give 'has-error' if the given field-name has errors
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
     * Should use the error message bag to give errors for a field
     */
    public function testErrorsFor()
    {
        $errors = [
            $this->generator()->anyString(),
            $this->generator()->anyString()
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
     * Should give old input when present
     */
    public function testOldInputOrWithOldInput()
    {
        $oldInputValue = $this->generator()->anyString();
        $fieldName = $this->generator()->anyString();

        $this->sessionStore->shouldReceive('getOldInput')
            ->andReturn([
                $fieldName => [$oldInputValue]
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
     * Should give the fallback string when old input is not present
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
