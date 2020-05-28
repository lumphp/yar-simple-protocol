<?php
namespace Lum\Yar\Packet;

use Lum\Server\Protocol\Reader;
use Lum\Server\Protocol\RequestPacket;
use Lum\Yar\Exception\YarServerException;
use Lum\Yar\YarRequest;
use Lum\Yar\YarRequestPacket;

/**
 * Class YarReader
 *
 * @package Lum\Yar\Packet
 */
final class YarReader implements Reader
{
    /**
     * @var string $bytes
     */
    private $bytes;
    private $pos;

    /**
     * YarReader constructor.
     *
     * @param string $data
     */
    public function __construct(string $data)
    {
        $this->bytes = $data;
        $this->pos = 0;
    }

    /**
     * @return RequestPacket|null
     * @throws YarServerException
     * @throws \Lum\Rpc\Exception\EncodeException
     */
    public function read() : ?RequestPacket
    {
        $header = $this->readHeader();
        if (!$header) {
            return null;
        }
        $bodyLen = $header->getLength();
        $body = $this->readBody($bodyLen);

        return new YarRequestPacket($header, $body);
    }

    /**
     * @param int $len
     *
     * @return YarRequestBody|null
     * @throws YarServerException
     * @throws \Lum\Rpc\Exception\EncodeException
     */
    protected function readBody(int $len) : ?YarRequestBody
    {
        $packager = $this->readPackager();
        $contentLen = $len - Yar::PACKAGER_SIZE;
        $request = $this->readRequest($packager, $contentLen);

        return new YarRequestBody($packager, $request);
    }

    /**
     * read yar header
     *
     * @return YarHeader
     * @throws YarServerException
     */
    protected function readHeader() : YarHeader
    {
        $header = substr($this->bytes, $this->pos, Yar::HEADER_SIZE);
        $magicNum = null;
        if ($header) {
            $this->pos += Yar::HEADER_SIZE;
            $data = unpack(Yar::REQUEST_HEADER_FORMAT, $header);
            $magicNum = $data['magicNum'] ?? null;
            if ($magicNum != Yar::MAGIC_NUM) {
                throw new YarServerException("illegal Yar RPC request", 1);
            }

            return new YarHeader($data);
        }
        throw new YarServerException("invalid header", 1);
    }

    /**
     * @return string
     * @throws YarServerException
     */
    protected function readPackager() : string
    {
        $str = str_replace(chr(0), '', substr($this->bytes, $this->pos, Yar::PACKAGER_SIZE));
        $this->pos += Yar::PACKAGER_SIZE;

        return Yar::getPackager($str);
    }

    /**
     * @param string $packager
     * @param int $len
     *
     * @return YarRequest|null
     * @throws \Lum\Rpc\Exception\EncodeException
     */
    protected function readRequest(string $packager, int $len) : ?YarRequest
    {
        $content = substr($this->bytes, $this->pos);
        if (!$content && strlen($content) !== $len) {
            return null;
        }
        $this->pos += $len;
        $encoder = Yar::getEncoderByPackager($packager);
        $data = $encoder->decode($content);
        if (!$data || !is_array($data) || !isset($data['i']) || !isset($data['m'])) {
            return null;
        }

        return new YarRequest($data['i'], $data['m'], $data['p'] ?? []);
    }
}