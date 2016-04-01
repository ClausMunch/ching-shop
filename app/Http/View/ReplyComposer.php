<?php

namespace ChingShop\Http\View;

use Illuminate\Contracts\View\View;
use Illuminate\Session\Store as SessionStore;
use Illuminate\Support\MessageBag;

class ReplyComposer
{
    const ERROR_CLASS = 'has-error';

    /** @var MessageBag */
    private $errors;

    /** @var MessageBag */
    private $oldInput;

    /** @var SessionStore */
    private $sessionStore;

    /**
     * @param SessionStore $sessionStore
     */
    public function __construct(SessionStore $sessionStore)
    {
        $this->sessionStore = $sessionStore;
    }

    /**
     * Bind a Location object to the view.
     *
     * @param View $view
     */
    public function compose(View $view)
    {
        $view->with(['reply' => $this]);
    }

    /**
     * @param string $fieldName
     *
     * @return string
     */
    public function putHasError(string $fieldName): string
    {
        if ($this->errors()->has($fieldName)) {
            return self::ERROR_CLASS;
        }

        return '';
    }

    /**
     * @param string $fieldName
     *
     * @return array
     */
    public function errorsFor(string $fieldName): array
    {
        return (array) $this->errors()->get($fieldName);
    }

    /**
     * @param string $fieldName
     * @param string $fallback
     *
     * @return string
     */
    public function oldInputOr(string $fieldName, string $fallback): string
    {
        $old = $this->oldInput()->get($fieldName);

        return isset($old[0]) ? (string) $old[0] : $fallback;
    }

    /**
     * @return MessageBag
     */
    private function errors()
    {
        if (!isset($this->errors)) {
            $this->errors = $this->sessionStore->get(
                'errors',
                new MessageBag([])
            );
        }

        return $this->errors;
    }

    /**
     * @return MessageBag
     */
    private function oldInput()
    {
        if (!isset($this->oldInput)) {
            $this->oldInput = new MessageBag(
                (array) $this->sessionStore->getOldInput()
            );
        }

        return $this->oldInput;
    }
}
