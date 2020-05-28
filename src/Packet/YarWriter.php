<?php
namespace Lum\Yar\Packet;

use Lum\Server\Protocol\Writer;
use Lum\Server\ServerResponse;

/**
 * Class YarWriter
 *
 * @package Lum\Yar\Packet
 */
final class YarWriter implements Writer
{
    /**
     * @var string $packet
     */
    private $data;

    /**
     * @param ServerResponse|null $response
     *
     * @return Writer
     * @throws \Lum\Rpc\Exception\EncodeException
     */
    public function write(?ServerResponse $response) : ?Writer
    {
        if ($response) {
            $this->writeResponse($response);
        }

        return $this;
    }

    /**
     * @return string
     */
    public function __toString() : string
    {
        return $this->data;
    }

    /**
     * @param ServerResponse $response
     *
     * @throws \Lum\Rpc\Exception\EncodeException
     */
    protected function writeResponse(ServerResponse $response) : void
    {
        $this->data = '';
        $result = $response->getResult();
        $status = $result->getStatus();
        $data = [
            'i' => 0,
            's' => $status,
        ];
        if ($status === 0) {
            $data["r"] = $result->getResult();
        } else {
            $data["e"] = $result->getResult();
        }
        $packager = $response->getPackager();
        $this->writeBody($packager, $data);
        $this->writeHeader(0, strlen($this->data));
    }

    /**
     * @param string $packager
     * @param array $result
     *
     * @throws \Lum\Rpc\Exception\EncodeException
     */
    private function writeBody(string $packager, $result) : void
    {
        $body = str_pad($packager, 8, "\0");
        $encoder = Yar::getEncoderByPackager($packager);
        $this->data = $body.$encoder->encode($result);
    }

    /**
     * @param int $id
     * @param int $length
     */
    protected function writeHeader(int $id, int $length) : void
    {
        $this->data = (new YarHeader(
                [
                    'id' => $id,
                    'version' => 0,
                    'magicNum' => Yar::MAGIC_NUM,
                    'reserved' => 0,
                    'provider' => "Yar PHP TCP Server",
                    'token' => "",
                    'length' => $length,
                ]
            ))->getEncoded().$this->data;
    }
}