<?php
namespace Lum\Yar;

use Lum\Server\Protocol\ResponseResult;

/**
 * Class YarResult
 *
 * @package Lum\Yar
 */
final class YarResult implements ResponseResult
{
    /**
     * @var int $status
     */
    private $status;
    /**
     * @var string|array $result
     */
    private $result;

    /**
     * @param int $status
     * @param $result
     */
    public function __construct(int $status, $result)
    {
        $this->status = $status;
        $this->result = $result;
    }

    /**
     * @return int
     */
    public function getStatus() : int
    {
        return $this->status;
    }

    /**
     * @return array|string
     */
    public function getResult()
    {
        return $this->result;
    }
}