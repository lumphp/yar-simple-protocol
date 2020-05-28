<?php
namespace Lum\Yar\Exception;

use Exception;
use Throwable;

/**
 * Class YarServerException
 *
 * @package Lum\Yar\Exception
 */
class YarServerException extends Exception
{
    /**
     * YarServerException constructor.
     *
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($message = "", int $code = 1, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}