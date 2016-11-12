<?php

namespace ChingShop\Modules\User\Http\Requests\Staff;

use ChingShop\Http\Requests\Staff\StaffRequest;

/**
 * Request to send a new Telegram message to the staff group.
 */
class TelegramMessageRequest extends StaffRequest
{
    const TEXT = 'text';

    /**
     * @return string
     */
    public function text(): string
    {
        return (string) $this->get(self::TEXT);
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            self::TEXT => 'required|string|min:1|max:256',
        ];
    }
}
