<?php

declare(strict_types=1);

namespace GoldSpecDigital\VoodooSmsSdk\Responses;

class SendSmsResponse extends Response
{
    /**
     * @return int
     */
    public function getCount(): int
    {
        return (int)$this->response['count'];
    }

    /**
     * @return string
     */
    public function getOriginator(): string
    {
        return $this->response['originator'];
    }

    /**
     * @return array
     */
    public function getBody(): string
    {
        return $this->response['body'];
    }

    /**
     * @return array
     */
    public function getScheduledDateTime(): int
    {
        return $this->response['scheduledDateTime'];
    }


    /**
     * @return int
     */
    public function getCredits(): int
    {
        return (int)$this->response['credits'];
    }

    /**
     * @return int
     */
    public function getBalance(): int
    {
        return (int)$this->response['balance'];
    }

    /**
     * @return int
     */
    public function getMessages(): array
    {
        return (array)$this->response['messages'];
    }

    /**
     * @return int
     */
    public function getStatus(): int
    {
        return $this->statusCode;
    }
}
