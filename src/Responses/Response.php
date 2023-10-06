<?php

declare(strict_types=1);

namespace GoldSpecDigital\VoodooSmsSdk\Responses;

abstract class Response
{
    /**
     * @var array
     */
    protected $response;

    /**
     * @var int
     */
    protected $statusCode;

    /**
     * SendSmsResponse constructor.
     *
     * @param array $response
     */
    public function __construct(array $response, int $statusCode)
    {
        $this->response = $response;
        $this->statusCode = $statusCode;
    }
}
