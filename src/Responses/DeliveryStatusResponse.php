<?php

declare(strict_types=1);

namespace GoldSpecDigital\VoodooSmsSdk\Responses;

class DeliveryStatusResponse extends Response
{
    /**
     * @return int
     */
    public function getLimit(): int
    {
        return (int)$this->response['limit'];
    }

    /**
     * @return int
     */
    public function getReport(): array
    {
        return (array)$this->response['report'];
    }

    /**
     * @return int
     */
    public function getStatus(): int
    {
        return $this->statusCode;
    }
}
