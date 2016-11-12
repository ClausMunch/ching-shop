<?php

namespace ChingShop\Http\Controllers\Staff;

use ChingShop\Http\Controllers\Controller;
use ChingShop\Http\WebUi;
use ChingShop\Modules\User\Domain\StaffTelegramGroup;
use ChingShop\Modules\User\Http\Requests\Staff\TelegramMessageRequest;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Telegram\Bot\Api;

/**
 * Staff telegram tool actions.
 */
class TelegramController extends Controller
{
    /** @var Api */
    private $telegram;

    /** @var WebUi */
    private $webUi;

    /**
     * TelegramController constructor.
     *
     * @param Api   $telegram
     * @param WebUi $webUi
     */
    public function __construct(Api $telegram, WebUi $webUi)
    {
        $this->telegram = $telegram->setAsyncRequest(false);
        $this->webUi = $webUi;
    }

    /**
     * @param Request $request
     *
     * @return View|JsonResponse
     */
    public function index(Request $request)
    {
        if ($request->isJson()) {
            return new JsonResponse(
                array_reverse(
                    $this->telegram->getUpdates()
                )
            );
        }

        return $this->webUi->view('staff.telegram.index');
    }

    /**
     * @param TelegramMessageRequest $request
     *
     * @return JsonResponse
     */
    public function store(TelegramMessageRequest $request)
    {
        $content = [
            'chat_id' => StaffTelegramGroup::id(),
            'text'    => sprintf(
                '(%s) %s',
                App::environment(),
                $request->text()
            ),
        ];

        try {
            $message = $this->telegram->sendMessage($content);

            $this->webUi->successMessage(
                "Sent message `{$message->getMessageId()}` to staff"
            );
        } catch (\Exception $e) {
            $this->webUi->errorMessage(
                sprintf(
                    'Failed to send message to staff: %s (%s)',
                    $e->getMessage(),
                    json_encode($content)
                )
            );
        }

        return $this->webUi->redirect('telegram.index');
    }
}
