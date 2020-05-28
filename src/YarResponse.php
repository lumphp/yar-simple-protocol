<?php
namespace Lum\Yar;

use Lum\Server\ServerResponse;

/**
 * Class YarResponse
 *
 * @package Lum\Yar
 */
final class YarResponse implements ServerResponse
{
    /**
     * @var YarResult $result
     */
    private $result;
    /**
     * @var string $packager
     */
    private $packager;

    /**
     * YarResponse constructor.
     *
     * @param string $packager
     * @param YarResult $result
     */
    public function __construct(string $packager, YarResult $result)
    {
        $this->result = $result;
        $this->packager = $packager;
    }

    /**
     * @return string
     */
    public function getPackager() : string
    {
        return $this->packager;
    }

    /**
     * @return YarResult|null
     */
    public function getResult() : ?YarResult
    {
        return $this->result;
    }

    /**
     * @return string
     */
    public function __toString() : string
    {
        return sprintf(
            '{packager=%s,result=%s}',
            $this->getPackager(),
            print_r($this->getResult(), true)
        );
    }
}