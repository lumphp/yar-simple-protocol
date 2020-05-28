<?php
namespace Lum\Yar;

use Lum\Server\Protocol\RequestPacket;
use Lum\Yar\Exception\YarServerException;
use Lum\Yar\Packet\YarHeader;
use Lum\Yar\Packet\YarRequestBody;

/**
 * Class YarRequestPacket
 *
 * @package Lum\Yar
 */
final class YarRequestPacket implements RequestPacket
{
    /**
     * @var YarHeader $header
     */
    private $header;
    /**
     * @var YarRequestBody $body
     */
    private $body;

    /**
     * Yar constructor.
     *
     * @param YarHeader $header
     * @param YarRequestBody $body
     */
    public function __construct(?YarHeader $header, ?YarRequestBody $body)
    {
        $this->header = $header;
        $this->body = $body;
    }

    /**
     * @return null|YarHeader
     */
    public function getRequestHeader() : ?YarHeader
    {
        return $this->header;
    }

    /**
     * @return null|YarRequestBody
     * @throws YarServerException
     */
    public function getRequestBody() : ?YarRequestBody
    {
        if (!$this->body) {
            throw new YarServerException('INVALID Request');
        }

        return $this->body;
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
}