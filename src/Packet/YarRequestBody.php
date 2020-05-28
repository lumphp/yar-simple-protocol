<?php
namespace Lum\Yar\Packet;

use Lum\Server\Protocol\Body;
use Lum\Server\ClientRequest;
use Lum\Yar\YarRequest;

/**
 * Class YarRequestBody
 *
 * @package Lum\Yar\Packet
 */
final class YarRequestBody implements Body
{
    private $request;
    private $packager;

    /**
     * YarRequestBody constructor.
     *
     * @param string $packager
     * @param null|YarRequest $request
     */
    public function __construct(string $packager, ?YarRequest $request = null)
    {
        $this->packager = $packager;
        $this->request = $request;
    }

    /**
     * @return string
     */
    public function getPackager() : string
    {
        return $this->packager;
    }

    /**
     * @return null|YarRequest
     */
    public function getRequest() : ?ClientRequest
    {
        return $this->request;
    }

    /**
     * @return string
     */
    public function __toString() : string
    {
        return sprintf(
            'YarRequestBody[packager=%s,request=%s]',
            $this->getPackager(),
            strval($this->request)
        );
    }
}