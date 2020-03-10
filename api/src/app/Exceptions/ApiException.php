<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Response;

class ApiException extends Exception
{
    /**
     * @var string
     */
    public string $title;

    /**
     * @var int
     */
    public int $statusCode;

    /**
     * @var array|null
     */
    public ?array $error;

    /**
     * ApiException constructor.
     * @param string $title
     * @param int $code
     * @param array|null $error
     */
    public function __construct(
        string $title = Error::INTERNAL_SERVER_ERROR,
        int $code = Response::HTTP_INTERNAL_SERVER_ERROR,
        ?array $error = []
    ) {
        parent::__construct($title);
        $this->title = $title;
        $this->statusCode = $code;
        $this->error = $error;
    }
}
