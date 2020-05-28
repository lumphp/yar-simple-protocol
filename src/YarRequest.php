<?php
namespace Lum\Yar;

use Lum\Server\ClientRequest;

/**
 * Class YarRequest
 *
 * @package Lum\Yar
 */
final class YarRequest implements ClientRequest
{
    private $requestId;
    private $method;
    private $params;

    /**
     * YarRequest constructor.
     *
     * @param int $id
     * @param string $method
     * @param array $params
     */
    public function __construct(int $id, string $method, array $params = [])
    {
        $this->requestId = $id;
        $this->method = $method;
        $this->params = $params;
    }

    /**
     * @return int
     */
    public function getRequestId() : int
    {
        return $this->requestId;
    }

    /**
     * @return string
     */
    public function getMethod() : string
    {
        return $this->method;
    }

    /**
     * @return array
     */
    public function getParams() : array
    {
        return $this->params;
    }

    /**
     * @return string
     */
    public function __toString() : string
    {
        return sprintf(
            '{id=%d,method=%s,params=%s}',
            $this->getRequestId(),
            $this->getMethod(),
            print_r($this->getParams(), true)
        );
    }
}