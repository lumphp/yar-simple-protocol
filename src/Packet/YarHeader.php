<?php
namespace Lum\Yar\Packet;

use Lum\Server\Protocol\Header;

/**
 * Class YarHeader
 *
 * @package Lum\Yar\Packet
 */
final class YarHeader implements Header
{
    private $id;
    private $version;
    private $magicNum;
    private $provider;
    private $token;
    private $length;
    private $reserved;

    /**
     * YarHeader constructor.
     *
     * @param array $header
     */
    public function __construct(array $header)
    {
        $this->setHeader($header);
    }

    /**
     * @param array $data
     */
    public function setHeader(array $data)
    {
        $this->id = $data['id'] ?? null;
        $this->version = $data['ver'] ?? null;
        $this->magicNum = $data['magicNum'];
        $this->reserved = $data['reserved'] ?? null;
        $this->provider = $data['provider'] ?? null;
        $this->token = $data['token'] ?? null;
        $this->length = intval($data['length'] ?? 0);
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * @return mixed
     */
    public function getMagicNum()
    {
        return $this->magicNum;
    }

    /**
     * @return mixed
     */
    public function getProvider()
    {
        return $this->provider;
    }

    /**
     * @return mixed
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @return mixed
     */
    public function getLength()
    {
        return $this->length;
    }

    /**
     * @return string
     */
    public function getEncoded() : string
    {
        $params = [
            $this->id,
            $this->version,
            $this->magicNum,
            $this->reserved,
            $this->provider,
            $this->token,
            $this->length,
        ];

        return pack(Yar::RESPONSE_HEADER_FORMAT, ...$params);
    }

    /**
     * @return string
     */
    public function __toString() : string
    {
        return sprintf(
            'header[id=0x%s,version=%s,magicNum=0x%s,provider=%s,token=%s,length=%s]',
            dechex($this->id),
            $this->version,
            dechex($this->magicNum),
            $this->provider,
            $this->token,
            $this->length
        );
    }
}