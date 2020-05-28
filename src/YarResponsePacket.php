<?php
namespace Lum\Yar;

use Lum\Server\Protocol\ResponseBody;
use Lum\Server\Protocol\ResponseHeader;
use Lum\Server\Protocol\ResponsePacket;
use Lum\Yar\Exception\YarServerException;

/**
 * Class YarResponsePacket
 *
 * @package Lum\Yar
 */
final class YarResponsePacket implements ResponsePacket
{
    /**
     * @var ResponseHeader $header
     */
    private $header;
    /**
     * @var ResponseBody $body
     */
    private $body;

    /**
     * Yar constructor.
     *
     * @param null|ResponseHeader $header
     * @param null|ResponseBody $body
     */
    public function __construct(?ResponseHeader $header, ?ResponseBody $body)
    {
        $this->header = $header;
        $this->body = $body;
    }

    /**
     * @return string
     */
    public function __toString() : string
    {
        return sprintf(
            "{\n\t%s,\n\t%s}",
            strval($this->header),
            strval($this->body)
        );
    }

    public function getResponseHeader() : ?ResponseHeader
    {
        return $this->header;
    }

    /**
     * @return ResponseBody|null
     * @throws YarServerException
     */
    public function getResponseBody() : ?ResponseBody
    {
        if (!$this->body) {
            throw new YarServerException('INVALID Response');
        }

        return $this->body;
    }
}